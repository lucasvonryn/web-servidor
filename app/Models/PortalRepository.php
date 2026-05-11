<?php

require_once __DIR__ . '/../Support/portal_helpers.php';

class PortalRepository
{
    private array $baseData;
    private $assetUrl;
    private PDO $pdo;

    public function __construct(array $baseData, callable $assetUrl, PDO $pdo)
    {
        $this->baseData = $baseData;
        $this->assetUrl = $assetUrl;
        $this->pdo      = $pdo;
    }

    public function getPortalData(): array
    {
        return $this->rebuildPortalData($this->readPortalDataFromDatabase());
    }

    public function persistPortalData(array $data): array
    {
        $portalData = $this->rebuildPortalData($data);
        $this->writePortalDataToDatabase($portalData);
        return $portalData;
    }

    private function readPortalDataFromDatabase(): array
    {
        $settingsRow = $this->pdo->query('SELECT * FROM settings WHERE id = 1')->fetch();
        $settings    = $settingsRow ? [
            'nome_site'          => $settingsRow['nome_site'] ?? '',
            'slogan'             => $settingsRow['slogan'] ?? '',
            'about_text'         => $settingsRow['about_text'] ?? '',
            'sobre'              => $settingsRow['about_text'] ?? '',
            'itens_home'         => (int) ($settingsRow['itens_home'] ?? 6),
            'show_featured'      => (bool) ($settingsRow['show_featured'] ?? true),
            'show_latest'        => (bool) ($settingsRow['show_latest'] ?? true),
            'exibir_comentarios' => (bool) ($settingsRow['exibir_comentarios'] ?? true),
            'contact_email'      => $settingsRow['contact_email'] ?? '',
            'footer_links'       => $settingsRow['footer_links'] ?? '',
        ] : [];

        $categories = [];
        $categoryRows = $this->pdo
            ->query('SELECT * FROM categories ORDER BY id ASC')
            ->fetchAll();

        foreach ($categoryRows as $row) {
            $slug              = (string) $row['slug'];
            $categories[$slug] = [
                'id'          => (int) $row['id'],
                'slug'        => $slug,
                'name'        => $row['name'],
                'tag_class'   => $row['tag_class'],
                'accent'      => $row['accent'],
                'cover'       => $this->formatAssetPath((string) ($row['cover'] ?? '')),
                'description' => $row['description'] ?? '',
            ];
        }

        $posts = [];
        $postRows = $this->pdo
            ->query(
                'SELECT posts.*, categories.slug AS category_slug
                 FROM posts
                 INNER JOIN categories ON categories.id = posts.category_id
                 ORDER BY posts.id DESC'
            )
            ->fetchAll();

        foreach ($postRows as $row) {
            $author       = (string) $row['author'];
            $authorShort  = (string) ($row['author_short'] ?? '');
            $posts[] = [
                'id'           => (int) $row['id'],
                'slug'         => $row['slug'],
                'title'        => $row['title'],
                'excerpt'      => $row['excerpt'] ?? '',
                'content'      => $row['content'],
                'category'     => $row['category_slug'],
                'author'       => $author,
                'author_short' => $authorShort !== '' ? $authorShort : $this->shortAuthorName($author),
                'date'         => $row['published_label'] ?: portal_format_date(strtotime((string) $row['created_at'])),
                'status'       => $row['status'],
                'featured'     => (bool) $row['featured'],
                'cover'        => $this->formatAssetPath((string) ($row['cover'] ?? '')),
            ];
        }

        $users = [];
        $userRows = $this->pdo
            ->query('SELECT * FROM users ORDER BY id ASC')
            ->fetchAll();

        foreach ($userRows as $row) {
            $users[] = [
                'id'         => (int) $row['id'],
                'nome'       => $row['nome'],
                'email'      => $row['email'],
                'papel'      => $row['papel'],
                'status'     => $row['status'],
                'created_at' => $this->formatDatabaseDate($row['created_at'] ?? null),
            ];
        }

        $comments = [];
        $commentRows = $this->pdo
            ->query('SELECT * FROM comments ORDER BY id DESC')
            ->fetchAll();

        foreach ($commentRows as $row) {
            $comments[] = [
                'id'      => (int) $row['id'],
                'post_id' => (int) $row['post_id'],
                'autor'   => $row['autor'],
                'email'   => $row['email'],
                'trecho'  => $row['trecho'] ?? portal_excerpt((string) $row['texto'], 220),
                'texto'   => $row['texto'],
                'status'  => $row['status'],
                'data'    => $row['published_label'] ?: portal_format_date(strtotime((string) $row['created_at'])),
            ];
        }

        return [
            'settings'   => $settings,
            'categories' => $categories,
            'posts'      => $posts,
            'users'      => $users,
            'comments'   => $comments,
        ];
    }

    private function writePortalDataToDatabase(array $portalData): void
    {
        $this->pdo->beginTransaction();

        try {
            $this->pdo->exec('DELETE FROM comments');
            $this->pdo->exec('DELETE FROM posts');
            $this->pdo->exec('DELETE FROM categories');
            $this->pdo->exec('DELETE FROM users');
            $this->pdo->exec('DELETE FROM settings');

            $this->insertSettings($portalData['settings'] ?? []);
            $this->insertUsers($portalData['users'] ?? []);
            $categoryIds = $this->insertCategories($portalData['categories'] ?? []);
            $this->insertPosts($portalData['posts'] ?? [], $categoryIds);
            $this->insertComments($portalData['comments'] ?? []);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $exception;
        }

        $this->resetAutoIncrement('users', $portalData['users'] ?? []);
        $this->resetAutoIncrement('categories', $portalData['categories'] ?? []);
        $this->resetAutoIncrement('posts', $portalData['posts'] ?? []);
        $this->resetAutoIncrement('comments', $portalData['comments'] ?? []);
    }

    private function insertSettings(array $settings): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO settings (
                id, nome_site, slogan, about_text, itens_home, show_featured, show_latest,
                exibir_comentarios, contact_email, footer_links
            ) VALUES (
                1, :nome_site, :slogan, :about_text, :itens_home, :show_featured, :show_latest,
                :exibir_comentarios, :contact_email, :footer_links
            )'
        );

        $stmt->execute([
            'nome_site'          => $settings['nome_site'] ?? 'O Editorial',
            'slogan'             => $settings['slogan'] ?? '',
            'about_text'         => $settings['about_text'] ?? ($settings['sobre'] ?? ''),
            'itens_home'         => (int) ($settings['itens_home'] ?? 6),
            'show_featured'      => ! empty($settings['show_featured']) ? 1 : 0,
            'show_latest'        => ! empty($settings['show_latest']) ? 1 : 0,
            'exibir_comentarios' => ! empty($settings['exibir_comentarios']) ? 1 : 0,
            'contact_email'      => $settings['contact_email'] ?? '',
            'footer_links'       => $settings['footer_links'] ?? '',
        ]);
    }

    private function insertUsers(array $users): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (id, nome, email, senha_hash, papel, status, created_at)
             VALUES (:id, :nome, :email, :senha_hash, :papel, :status, :created_at)'
        );

        foreach ($users as $user) {
            $stmt->execute([
                'id'         => (int) ($user['id'] ?? 0),
                'nome'       => $user['nome'] ?? '',
                'email'      => $user['email'] ?? '',
                'senha_hash' => $user['senha_hash'] ?? null,
                'papel'      => $user['papel'] ?? 'Editor',
                'status'     => $user['status'] ?? 'Ativo',
                'created_at' => $this->parseBrazilianDate($user['created_at'] ?? null),
            ]);
        }
    }

    private function insertCategories(array $categories): array
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO categories (id, slug, name, tag_class, accent, cover, description)
             VALUES (:id, :slug, :name, :tag_class, :accent, :cover, :description)'
        );

        $categoryIds = [];
        foreach ($categories as $slug => $category) {
            $categorySlug = (string) ($category['slug'] ?? $slug);
            $categoryId   = (int) ($category['id'] ?? 0);
            $categoryIds[$categorySlug] = $categoryId;

            $stmt->execute([
                'id'          => $categoryId,
                'slug'        => $categorySlug,
                'name'        => $category['name'] ?? $categorySlug,
                'tag_class'   => $category['tag_class'] ?? $categorySlug,
                'accent'      => $category['accent'] ?? 'tech',
                'cover'       => $this->stripAssetBase((string) ($category['cover'] ?? '')),
                'description' => $category['description'] ?? '',
            ]);
        }

        return $categoryIds;
    }

    private function insertPosts(array $posts, array $categoryIds): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO posts (
                id, category_id, slug, title, excerpt, content, author, author_short,
                published_label, status, featured, cover
            ) VALUES (
                :id, :category_id, :slug, :title, :excerpt, :content, :author, :author_short,
                :published_label, :status, :featured, :cover
            )'
        );

        foreach ($posts as $post) {
            $categorySlug = (string) ($post['category'] ?? '');
            if (! isset($categoryIds[$categorySlug])) {
                continue;
            }

            $stmt->execute([
                'id'              => (int) ($post['id'] ?? 0),
                'category_id'     => $categoryIds[$categorySlug],
                'slug'            => $post['slug'] ?? '',
                'title'           => $post['title'] ?? '',
                'excerpt'         => $post['excerpt'] ?? '',
                'content'         => $post['content'] ?? '',
                'author'          => $post['author'] ?? '',
                'author_short'    => $post['author_short'] ?? $this->shortAuthorName((string) ($post['author'] ?? '')),
                'published_label' => $post['date'] ?? null,
                'status'          => $post['status'] ?? 'Rascunho',
                'featured'        => ! empty($post['featured']) ? 1 : 0,
                'cover'           => $this->stripAssetBase((string) ($post['cover'] ?? '')),
            ]);
        }
    }

    private function insertComments(array $comments): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comments (id, post_id, autor, email, trecho, texto, status, published_label)
             VALUES (:id, :post_id, :autor, :email, :trecho, :texto, :status, :published_label)'
        );

        foreach ($comments as $comment) {
            $stmt->execute([
                'id'              => (int) ($comment['id'] ?? 0),
                'post_id'         => (int) ($comment['post_id'] ?? 0),
                'autor'           => $comment['autor'] ?? 'Leitor',
                'email'           => $comment['email'] ?? '',
                'trecho'          => $comment['trecho'] ?? portal_excerpt((string) ($comment['texto'] ?? ''), 220),
                'texto'           => $comment['texto'] ?? '',
                'status'          => $comment['status'] ?? 'Pendente',
                'published_label' => $comment['data'] ?? null,
            ]);
        }
    }

    private function resetAutoIncrement(string $table, array $items): void
    {
        $allowedTables = ['users', 'categories', 'posts', 'comments'];
        if (! in_array($table, $allowedTables, true)) {
            throw new InvalidArgumentException('Tabela inválida para ajuste de AUTO_INCREMENT.');
        }

        $nextId = 1;
        foreach ($items as $item) {
            $nextId = max($nextId, (int) ($item['id'] ?? 0) + 1);
        }

        $this->pdo->exec(sprintf('ALTER TABLE %s AUTO_INCREMENT = %d', $table, $nextId));
    }

    private function formatAssetPath(string $path): string
    {
        $path = trim($path);
        if ($path === '' || preg_match('/^https?:\/\//', $path) === 1 || str_starts_with($path, '/')) {
            return $path;
        }

        return ($this->assetUrl)($path);
    }

    private function stripAssetBase(string $path): string
    {
        $path = trim($path);
        if ($path === '' || preg_match('/^https?:\/\//', $path) === 1) {
            return $path;
        }

        $publicIndex = strpos($path, 'assets/');
        if ($publicIndex !== false) {
            return substr($path, $publicIndex);
        }

        return ltrim($path, '/');
    }

    private function formatDatabaseDate(?string $date): string
    {
        if (! $date) {
            return portal_format_date();
        }

        $timestamp = strtotime($date);
        return $timestamp ? date('d/m/Y', $timestamp) : $date;
    }

    private function parseBrazilianDate(?string $date): ?string
    {
        $date = trim((string) $date);
        if ($date === '') {
            return null;
        }

        $parts = explode('/', $date);
        if (count($parts) === 3) {
            return sprintf('%04d-%02d-%02d', (int) $parts[2], (int) $parts[1], (int) $parts[0]);
        }

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1 ? $date : null;
    }

    private function shortAuthorName(string $author): string
    {
        $parts = preg_split('/\s+/', trim($author)) ?: [];
        return $parts[0] ?? $author;
    }

    private function rebuildPortalData(array $data): array
    {
        $baseSettings       = is_array($this->baseData['settings'] ?? null) ? $this->baseData['settings'] : [];
        $data['settings']   = array_merge($baseSettings, is_array($data['settings'] ?? null) ? $data['settings'] : []);
        $data['categories'] = is_array($data['categories'] ?? null) ? $data['categories'] : [];
        $data['posts']      = array_values(is_array($data['posts'] ?? null) ? $data['posts'] : []);
        $data['users']      = array_values(is_array($data['users'] ?? null) ? $data['users'] : []);
        $data['comments']   = array_values(is_array($data['comments'] ?? null) ? $data['comments'] : []);

        $baseCategories       = is_array($this->baseData['categories'] ?? null) ? $this->baseData['categories'] : [];
        $normalizedCategories = [];

        foreach ($baseCategories as $slug => $baseCategory) {
            $sessionCategory = $data['categories'][$slug] ?? null;
            if (is_array($sessionCategory)) {
                $normalizedCategories[$slug] = array_merge($baseCategory, [
                    'id'          => $sessionCategory['id'] ?? $baseCategory['id'],
                    'count'       => $sessionCategory['count'] ?? null,
                    'count_label' => $sessionCategory['count_label'] ?? null,
                ], ['slug' => $slug]);
                continue;
            }

            foreach ($data['categories'] as $rawCategory) {
                if (! is_array($rawCategory)) {
                    continue;
                }

                if (($rawCategory['slug'] ?? null) === $slug) {
                    $normalizedCategories[$slug] = array_merge($baseCategory, [
                        'id'          => $rawCategory['id'] ?? $baseCategory['id'],
                        'count'       => $rawCategory['count'] ?? null,
                        'count_label' => $rawCategory['count_label'] ?? null,
                    ], ['slug' => $slug]);
                    continue 2;
                }
            }

            $normalizedCategories[$slug] = $baseCategory;
        }

        foreach ($data['categories'] as $key => $rawCategory) {
            if (! is_array($rawCategory)) {
                continue;
            }

            $slug = trim((string) ($rawCategory['slug'] ?? (is_string($key) ? $key : '')));
            if ($slug === '' || isset($normalizedCategories[$slug])) {
                continue;
            }

            $normalizedCategories[$slug] = $rawCategory;
        }

        $data['categories'] = $normalizedCategories;

        usort($data['posts'], static function (array $left, array $right): int {
            return ($right['id'] ?? 0) <=> ($left['id'] ?? 0);
        });

        $defaultCategoryCover = '';
        foreach ($data['categories'] as $category) {
            if (! empty($category['cover'])) {
                $defaultCategoryCover = $category['cover'];
                break;
            }
        }

        if ($defaultCategoryCover === '') {
            $defaultCategoryCover = ($this->assetUrl)('assets/home/tecnologia-capa.png');
        }

        $postCounts = [];
        foreach ($data['posts'] as $post) {
            $categorySlug = $post['category'] ?? '';
            if ($categorySlug === '') {
                continue;
            }
            $postCounts[$categorySlug] = ($postCounts[$categorySlug] ?? 0) + 1;
        }

        foreach ($data['categories'] as $slug => &$category) {
            $baseCategory            = $baseCategories[$slug] ?? [];
            $count                   = $postCounts[$slug] ?? 0;
            $category['id']          = $category['id'] ?? ($baseCategory['id'] ?? ($count + 1));
            $category['slug']        = $slug;
            $category['name']        = trim((string) ($category['name'] ?? $baseCategory['name'] ?? $slug));
            $category['tag_class']   = trim((string) ($category['tag_class'] ?? $baseCategory['tag_class'] ?? $slug));
            $category['accent']      = trim((string) ($category['accent'] ?? $baseCategory['accent'] ?? 'tech'));
            $category['cover']       = trim((string) ($category['cover'] ?? $baseCategory['cover'] ?? $defaultCategoryCover));
            $category['description'] = trim((string) ($category['description'] ?? $baseCategory['description'] ?? ''));
            $category['count']       = $count;
            $category['count_label'] = $count . ' ' . ($count === 1 ? 'publicação' : 'publicações');
        }
        unset($category);

        $featuredSlides = array_values(array_filter($data['posts'], static function (array $post): bool {
            return ($post['status'] ?? 'Publicado') === 'Publicado' && ! empty($post['featured']);
        }));

        if (empty($featuredSlides)) {
            $featuredSlides = array_values(array_filter($data['posts'], static function (array $post): bool {
                return ($post['status'] ?? 'Publicado') === 'Publicado';
            }));
            $featuredSlides = array_slice($featuredSlides, 0, 3);
        }

        $data['featured_slides'] = $featuredSlides;

        return $data;
    }
}
