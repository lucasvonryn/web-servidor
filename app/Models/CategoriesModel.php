<?php

require_once __DIR__ . '/PortalRepository.php';
require_once __DIR__ . '/../Support/portal_helpers.php';

class CategoriesModel
{
    private PortalRepository $repo;
    private $assetUrl;

    public function __construct(PortalRepository $repo, callable $assetUrl)
    {
        $this->repo = $repo;
        $this->assetUrl = $assetUrl;
    }

    public function save(array $portalData, array $input): array
    {
        $originalSlug = trim((string) ($input['original_slug'] ?? ''));
        $nome = trim((string) ($input['nome'] ?? ''));
        $slugInput = trim((string) ($input['slug'] ?? ''));
        $descricao = trim((string) ($input['description'] ?? ''));
        $accentInput = trim((string) ($input['accent'] ?? ''));

        if ($nome === '') {
            portal_set_alert('danger', 'Informe o nome da categoria.');
            return $portalData;
        }

        $categories = $portalData['categories'];
        $nextId = 1;
        foreach ($categories as $category) {
            $nextId = max($nextId, (int) ($category['id'] ?? 0) + 1);
        }

        $baseSlug = portal_slugify($slugInput !== '' ? $slugInput : $nome);
        $slug = $baseSlug;
        $slugSuffix = 2;
        while (isset($categories[$slug]) && $slug !== $originalSlug) {
            $slug = $baseSlug . '-' . $slugSuffix;
            $slugSuffix++;
        }

        $accentOptions = ['tech', 'politics', 'science', 'green', 'culture'];
        $accent = in_array($accentInput, $accentOptions, true) ? $accentInput : $accentOptions[($nextId - 1) % count($accentOptions)];
        $fallbackCover = ($this->assetUrl)('assets/home/tecnologia-capa.png');
        foreach ($categories as $category) {
            if (!empty($category['cover'])) {
                $fallbackCover = $category['cover'];
                break;
            }
        }

        if ($originalSlug !== '' && isset($categories[$originalSlug])) {
            $currentCategory = $categories[$originalSlug];
            unset($categories[$originalSlug]);
            $categories[$slug] = [
                'id' => $currentCategory['id'] ?? 0,
                'slug' => $slug,
                'name' => $nome,
                'tag_class' => $slug,
                'accent' => $accent,
                'cover' => $currentCategory['cover'] ?? $fallbackCover,
                'description' => $descricao !== '' ? $descricao : ('Conteúdos e análises sobre ' . strtolower($nome) . '.'),
            ];

            foreach ($portalData['posts'] as &$postItem) {
                if (($postItem['category'] ?? '') === $originalSlug) {
                    $postItem['category'] = $slug;
                }
            }
            unset($postItem);
        } else {
            $nextId = 1;
            foreach ($categories as $category) {
                $nextId = max($nextId, (int) ($category['id'] ?? 0) + 1);
            }
            $accent = in_array($accentInput, $accentOptions, true) ? $accentInput : $accentOptions[($nextId - 1) % count($accentOptions)];
            $categories[$slug] = [
                'id' => $nextId,
                'slug' => $slug,
                'name' => $nome,
                'tag_class' => $slug,
                'accent' => $accent,
                'cover' => $fallbackCover,
                'description' => $descricao !== '' ? $descricao : ('Conteúdos e análises sobre ' . strtolower($nome) . '.'),
            ];
        }

        $portalData['categories'] = $categories;
        return $this->repo->persistPortalData($portalData);
    }

    public function delete(array $portalData, string $slug): array
    {
        $slug = trim($slug);
        if ($slug === '') {
            return $portalData;
        }

        foreach ($portalData['posts'] as $postItem) {
            if (($postItem['category'] ?? '') === $slug) {
                portal_set_alert('danger', 'Não é possível excluir uma categoria que ainda possui publicações.');
                return $portalData;
            }
        }

        if (isset($portalData['categories'][$slug])) {
            unset($portalData['categories'][$slug]);
        }

        return $this->repo->persistPortalData($portalData);
    }
}

