<?php

require_once __DIR__ . '/PortalRepository.php';
require_once __DIR__ . '/../Support/portal_helpers.php';

class PostsModel
{
    private PortalRepository $repo;

    public function __construct(PortalRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Retorna null quando ok; caso contrário retorna array de erros.
     */
    public function validate(array $portalData, array $input): ?array
    {
        $erros = [];

        if (trim((string) ($input['titulo'] ?? '')) === '') {
            $erros['titulo'] = 'Informe o título da publicação.';
        }

        $categoria = trim((string) ($input['categoria'] ?? ''));
        if ($categoria === '' || ! isset($portalData['categories'][$categoria])) {
            $erros['categoria'] = 'Selecione uma categoria válida.';
        }

        if (trim((string) ($input['conteudo'] ?? '')) === '') {
            $erros['conteudo'] = 'Escreva o conteúdo da publicação.';
        }

        return $erros ? $erros : null;
    }

    public function save(array $portalData, array $input): array
    {
        $postId         = (int) ($input['id'] ?? 0);
        $titulo         = trim((string) ($input['titulo'] ?? ''));
        $slugInput      = trim((string) ($input['slug'] ?? ''));
        $resumo         = trim((string) ($input['resumo'] ?? ''));
        $categoria      = trim((string) ($input['categoria'] ?? ''));
        $status         = trim((string) ($input['status'] ?? 'Rascunho'));
        $autor          = trim((string) ($input['autor'] ?? ''));
        $dataPublicacao = trim((string) ($input['data_publicacao'] ?? ''));
        $coverUrl       = trim((string) ($input['cover'] ?? ''));
        $featured       = ! empty($input['featured']);
        $conteudo       = trim((string) ($input['conteudo'] ?? ''));

        if (! in_array($status, ['Publicado', 'Rascunho'], true)) {
            $status = 'Rascunho';
        }

        if ($autor === '') {
            $autor = trim((string) ($_SESSION['usuario_nome'] ?? 'Administrador'));
        }

        if ($resumo === '') {
            $resumo = portal_excerpt($conteudo);
        }

        $posts         = $portalData['posts'];
        $baseSlug      = portal_slugify($slugInput !== '' ? $slugInput : $titulo);
        $slug          = $baseSlug;
        $existingSlugs = [];
        foreach ($posts as $existingPost) {
            if ($postId > 0 && (int) ($existingPost['id'] ?? 0) === $postId) {
                continue;
            }
            $existingSlugs[] = $existingPost['slug'];
        }
        $slugSuffix = 2;
        while (in_array($slug, $existingSlugs, true)) {
            $slug = $baseSlug . '-' . $slugSuffix;
            $slugSuffix++;
        }

        $authorParts      = preg_split('/\s+/', $autor) ?: [];
        $authorShort      = $authorParts[0] ?? $autor;
        $selectedCategory = $portalData['categories'][$categoria] ?? null;
        $postDate         = $dataPublicacao !== '' ? $dataPublicacao : portal_format_date();

        $payload = [
            'slug'         => $slug,
            'title'        => $titulo,
            'excerpt'      => $resumo,
            'content'      => $conteudo,
            'category'     => $categoria,
            'author'       => $autor,
            'author_short' => $authorShort,
            'date'         => $postDate,
            'status'       => $status,
            'featured'     => $featured,
            'cover'        => $coverUrl !== '' ? $coverUrl : ($selectedCategory['cover'] ?? ''),
        ];

        if ($postId > 0) {
            foreach ($posts as &$postItem) {
                if ((int) ($postItem['id'] ?? 0) === $postId) {
                    $postItem = array_merge($postItem, $payload);
                    break;
                }
            }
            unset($postItem);
        } else {
            $nextId = 1;
            foreach ($posts as $post) {
                $nextId = max($nextId, (int) ($post['id'] ?? 0) + 1);
            }
            $posts[] = array_merge(['id' => $nextId], $payload);
        }

        $portalData['posts'] = $posts;
        return $this->repo->persistPortalData($portalData);
    }

    public function delete(array $portalData, int $postId): array
    {
        $posts       = $portalData['posts'];
        $removedPost = false;

        foreach ($posts as $index => $postItem) {
            if ((int) ($postItem['id'] ?? 0) === $postId) {
                unset($posts[$index]);
                $removedPost = true;
                break;
            }
        }

        if ($removedPost) {
            $portalData['posts']    = array_values($posts);
            $portalData['comments'] = array_values(array_filter($portalData['comments'], static function (array $commentItem) use ($postId): bool {
                return (int) ($commentItem['post_id'] ?? 0) !== $postId;
            }));
        }

        return $this->repo->persistPortalData($portalData);
    }
}
