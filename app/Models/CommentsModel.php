<?php

require_once __DIR__ . '/PortalRepository.php';
require_once __DIR__ . '/../Support/portal_helpers.php';

class CommentsModel
{
    private PortalRepository $repo;

    public function __construct(PortalRepository $repo)
    {
        $this->repo = $repo;
    }

    public function setStatus(array $portalData, int $commentId, string $status): array
    {
        $allowedStatuses = ['Aprovado', 'Pendente', 'Rejeitado'];
        if ($commentId <= 0 || ! in_array($status, $allowedStatuses, true)) {
            return $portalData;
        }

        foreach ($portalData['comments'] as &$commentItem) {
            if ((int) ($commentItem['id'] ?? 0) === $commentId) {
                $commentItem['status'] = $status;
                break;
            }
        }
        unset($commentItem);

        return $this->repo->persistPortalData($portalData);
    }

    public function delete(array $portalData, int $commentId): array
    {
        if ($commentId <= 0) {
            return $portalData;
        }

        $portalData['comments'] = array_values(array_filter($portalData['comments'], static function (array $commentItem) use ($commentId): bool {
            return (int) ($commentItem['id'] ?? 0) !== $commentId;
        }));

        return $this->repo->persistPortalData($portalData);
    }

    public function addPublicComment(array $portalData, string $postSlug, string $text): ?array
    {
        $postSlug = trim($postSlug);
        $text     = trim($text);
        if ($postSlug === '' || $text === '') {
            return null;
        }

        $targetPost = null;
        foreach ($portalData['posts'] as $post) {
            if (($post['slug'] ?? '') === $postSlug) {
                $targetPost = $post;
                break;
            }
        }
        if (! $targetPost) {
            return null;
        }

        $comments = $portalData['comments'];
        $nextId   = 1;
        foreach ($comments as $comment) {
            $nextId = max($nextId, (int) ($comment['id'] ?? 0) + 1);
        }

        $commentAuthor  = trim((string) ($_SESSION['usuario_publico_nome'] ?? ''));
        $commentEmail   = trim((string) ($_SESSION['usuario_publico_email'] ?? 'leitor@oeditorial.com.br'));
        $commentExcerpt = portal_excerpt($text, 220);

        $comments[] = [
            'id'      => $nextId,
            'post_id' => (int) ($targetPost['id'] ?? 0),
            'autor'   => $commentAuthor !== '' ? $commentAuthor : 'Leitor',
            'email'   => $commentEmail,
            'trecho'  => $commentExcerpt,
            'texto'   => $text,
            'status'  => 'Aprovado',
            'data'    => portal_format_date(),
        ];

        $portalData['comments'] = $comments;
        return $this->repo->persistPortalData($portalData);
    }
}
