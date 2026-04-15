<?php

require_once __DIR__ . '/../Support/portal_helpers.php';

class PortalRepository
{
    private array $baseData;
    private $assetUrl;

    public function __construct(array $baseData, callable $assetUrl)
    {
        $this->baseData = $baseData;
        $this->assetUrl = $assetUrl;
    }

    public function getPortalData(): array
    {
        $sessionPortalData = $_SESSION['portal_data'] ?? null;
        $portalData = $this->rebuildPortalData(is_array($sessionPortalData) ? $sessionPortalData : $this->baseData);
        if (is_array($sessionPortalData)) {
            $_SESSION['portal_data'] = $portalData;
        }
        return $portalData;
    }

    public function persistPortalData(array $data): array
    {
        $portalData = $this->rebuildPortalData($data);
        $_SESSION['portal_data'] = $portalData;
        return $portalData;
    }

    private function rebuildPortalData(array $data): array
    {
        $baseSettings = is_array($this->baseData['settings'] ?? null) ? $this->baseData['settings'] : [];
        $data['settings'] = array_merge($baseSettings, is_array($data['settings'] ?? null) ? $data['settings'] : []);
        $data['categories'] = is_array($data['categories'] ?? null) ? $data['categories'] : [];
        $data['posts'] = array_values(is_array($data['posts'] ?? null) ? $data['posts'] : []);
        $data['users'] = array_values(is_array($data['users'] ?? null) ? $data['users'] : []);
        $data['comments'] = array_values(is_array($data['comments'] ?? null) ? $data['comments'] : []);

        $baseCategories = is_array($this->baseData['categories'] ?? null) ? $this->baseData['categories'] : [];
        $normalizedCategories = [];

        foreach ($baseCategories as $slug => $baseCategory) {
            $sessionCategory = $data['categories'][$slug] ?? null;
            if (is_array($sessionCategory)) {
                $normalizedCategories[$slug] = array_merge($baseCategory, [
                    'id' => $sessionCategory['id'] ?? $baseCategory['id'],
                    'count' => $sessionCategory['count'] ?? null,
                    'count_label' => $sessionCategory['count_label'] ?? null,
                ], ['slug' => $slug]);
                continue;
            }

            foreach ($data['categories'] as $rawCategory) {
                if (!is_array($rawCategory)) {
                    continue;
                }

                if (($rawCategory['slug'] ?? null) === $slug) {
                    $normalizedCategories[$slug] = array_merge($baseCategory, [
                        'id' => $rawCategory['id'] ?? $baseCategory['id'],
                        'count' => $rawCategory['count'] ?? null,
                        'count_label' => $rawCategory['count_label'] ?? null,
                    ], ['slug' => $slug]);
                    continue 2;
                }
            }

            $normalizedCategories[$slug] = $baseCategory;
        }

        foreach ($data['categories'] as $key => $rawCategory) {
            if (!is_array($rawCategory)) {
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
            if (!empty($category['cover'])) {
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
            $baseCategory = $baseCategories[$slug] ?? [];
            $count = $postCounts[$slug] ?? 0;
            $category['id'] = $category['id'] ?? ($baseCategory['id'] ?? ($count + 1));
            $category['slug'] = $slug;
            $category['name'] = trim((string) ($category['name'] ?? $baseCategory['name'] ?? $slug));
            $category['tag_class'] = trim((string) ($category['tag_class'] ?? $baseCategory['tag_class'] ?? $slug));
            $category['accent'] = trim((string) ($category['accent'] ?? $baseCategory['accent'] ?? 'tech'));
            $category['cover'] = trim((string) ($category['cover'] ?? $baseCategory['cover'] ?? $defaultCategoryCover));
            $category['description'] = trim((string) ($category['description'] ?? $baseCategory['description'] ?? ''));
            $category['count'] = $count;
            $category['count_label'] = $count . ' ' . ($count === 1 ? 'publicação' : 'publicações');
        }
        unset($category);

        $featuredSlides = array_values(array_filter($data['posts'], static function (array $post): bool {
            return ($post['status'] ?? 'Publicado') === 'Publicado' && !empty($post['featured']);
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

