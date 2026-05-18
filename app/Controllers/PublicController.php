<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\CommentsModel;
use App\Models\PortalRepository;

class PublicController
{
    private View $view;
    private PortalRepository $repo;
    private CommentsModel $commentsModel;
    private $routeUrl;
    private $assetUrl;

    public function __construct(View $view, PortalRepository $repo, callable $routeUrl, callable $assetUrl)
    {
        $this->view          = $view;
        $this->repo          = $repo;
        $this->commentsModel = new CommentsModel($repo);
        $this->routeUrl      = $routeUrl;
        $this->assetUrl      = $assetUrl;
    }

    private function baseViewData(array $portalData): array
    {
        return [
            'portalData' => $portalData,
            'routeUrl'   => $this->routeUrl,
            'assetUrl'   => $this->assetUrl,
        ];
    }

    public function home(): void
    {
        $portalData = $this->repo->getPortalData();
        $this->view->render('public/home.php', $this->baseViewData($portalData));
    }

    public function login(): void
    {
        $portalData = $this->repo->getPortalData();
        $this->view->render('public/login.php', $this->baseViewData($portalData));
    }

    public function categoria(): void
    {
        $portalData = $this->repo->getPortalData();
        $this->view->render('public/categoria.php', $this->baseViewData($portalData));
    }

    public function publicacoes(): void
    {
        $portalData = $this->repo->getPortalData();
        $this->view->render('public/publicacoes.php', $this->baseViewData($portalData));
    }

    public function publicacao(): void
    {
        $portalData = $this->repo->getPortalData();
        $this->view->render('public/publicacao.php', $this->baseViewData($portalData));
    }

    public function conta(): void
    {
        portal_require_public_user($this->routeUrl, 'Entre com sua conta pública para acessar sua área.');
        $portalData = $this->repo->getPortalData();
        $this->view->render('public/conta.php', $this->baseViewData($portalData));
    }

    public function comentar(): void
    {
        portal_require_public_user($this->routeUrl, 'Você precisa entrar com uma conta pública para comentar.');

        $portalData  = $this->repo->getPortalData();
        $postSlug    = trim($_POST['slug'] ?? '');
        $redirectUrl = ($this->routeUrl)('publicacao', ['slug' => $postSlug]);

        if (empty($portalData['settings']['exibir_comentarios'])) {
            portal_set_alert('danger', 'Os comentários estão desativados no momento.');
            portal_redirect($redirectUrl);
        }

        $commentText = trim($_POST['comentario'] ?? '');
        if ($postSlug === '' || $commentText === '') {
            portal_set_alert('danger', 'Escreva um comentário antes de enviar.');
            portal_redirect($redirectUrl);
        }

        $saved = $this->commentsModel->addPublicComment($portalData, $postSlug, $commentText);
        if (! $saved) {
            http_response_code(404);
            echo '<h1>404 - Publicação não encontrada</h1>';
            exit;
        }

        portal_set_alert('success', 'Comentário enviado com sucesso e aguardando aprovação da equipe.');
        portal_redirect($redirectUrl . '#comentarios');
    }
}
