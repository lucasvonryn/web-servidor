<?php

require_once __DIR__ . '/../Core/View.php';
require_once __DIR__ . '/../Models/PostsModel.php';
require_once __DIR__ . '/../Models/CategoriesModel.php';
require_once __DIR__ . '/../Models/UsersModel.php';
require_once __DIR__ . '/../Models/CommentsModel.php';
require_once __DIR__ . '/../Models/SettingsModel.php';
require_once __DIR__ . '/../Models/PortalRepository.php';
require_once __DIR__ . '/../Support/portal_helpers.php';

class AdminController
{
    private View $view;
    private PortalRepository $repo;
    private $routeUrl;
    private $assetUrl;
    private PostsModel $postsModel;
    private CategoriesModel $categoriesModel;
    private UsersModel $usersModel;
    private CommentsModel $commentsModel;
    private SettingsModel $settingsModel;

    public function __construct(
        View $view,
        PortalRepository $repo,
        callable $routeUrl,
        callable $assetUrl,
        PostsModel $postsModel,
        CategoriesModel $categoriesModel,
        UsersModel $usersModel,
        CommentsModel $commentsModel,
        SettingsModel $settingsModel
    ) {
        $this->view            = $view;
        $this->repo            = $repo;
        $this->routeUrl        = $routeUrl;
        $this->assetUrl        = $assetUrl;
        $this->postsModel      = $postsModel;
        $this->categoriesModel = $categoriesModel;
        $this->usersModel      = $usersModel;
        $this->commentsModel   = $commentsModel;
        $this->settingsModel   = $settingsModel;
    }

    private function baseViewData(array $portalData): array
    {
        return [
            'portalData' => $portalData,
            'routeUrl'   => $this->routeUrl,
            'assetUrl'   => $this->assetUrl,
        ];
    }

    private function requireAdmin(): void
    {
        portal_require_admin($this->routeUrl);
    }

    public function login(): void
    {
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/login.php', $this->baseViewData($portalData));
    }

    public function postsLista(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/posts/lista.php', $this->baseViewData($portalData));
    }

    public function postsCadastro(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/posts/cadastro.php', $this->baseViewData($portalData));
    }

    public function postsSalvar(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $erros      = $this->postsModel->validate($portalData, $_POST);
        if ($erros) {
            $_SESSION['erros'] = $erros;
            $_SESSION['old']   = [
                'titulo'          => trim((string) ($_POST['titulo'] ?? '')),
                'slug'            => trim((string) ($_POST['slug'] ?? '')),
                'resumo'          => trim((string) ($_POST['resumo'] ?? '')),
                'categoria'       => trim((string) ($_POST['categoria'] ?? '')),
                'status'          => trim((string) ($_POST['status'] ?? 'Rascunho')),
                'autor'           => trim((string) ($_POST['autor'] ?? '')),
                'data_publicacao' => trim((string) ($_POST['data_publicacao'] ?? '')),
                'cover'           => trim((string) ($_POST['cover'] ?? '')),
                'featured'        => ! empty($_POST['featured']),
                'conteudo'        => trim((string) ($_POST['conteudo'] ?? '')),
            ];
            $postId = (int) ($_POST['id'] ?? 0);
            portal_redirect(($this->routeUrl)($postId > 0 ? 'admin/posts/editar' : 'admin/posts/novo', $postId > 0 ? ['id' => $postId] : []));
        }

        $postId     = (int) ($_POST['id'] ?? 0);
        $status     = trim((string) ($_POST['status'] ?? 'Rascunho'));
        $portalData = $this->postsModel->save($portalData, $_POST);
        $message    = $postId > 0
            ? 'Publicação atualizada com sucesso.'
            : ($status === 'Publicado'
                ? 'Postagem publicada com sucesso e já disponível no portal.'
                : 'Rascunho salvo com sucesso.');
        portal_set_alert('success', $message);
        portal_redirect(($this->routeUrl)('admin/posts'));
    }

    public function postsExcluir(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $postId     = (int) ($_GET['id'] ?? 0);
        $this->postsModel->delete($portalData, $postId);
        portal_set_alert('success', 'Publicação removida com sucesso.');
        portal_redirect(($this->routeUrl)('admin/posts'));
    }

    public function usuariosLista(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/usuarios/lista.php', $this->baseViewData($portalData));
    }

    public function usuariosCadastro(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/usuarios/cadastro.php', $this->baseViewData($portalData));
    }

    public function usuariosSalvar(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $userId     = (int) ($_POST['id'] ?? 0);
        $saved      = $this->usersModel->save($portalData, $_POST);
        if ($saved !== $portalData) {
            portal_set_alert('success', $userId > 0 ? 'Usuário atualizado com sucesso!' : 'Membro da equipe salvo com sucesso!');
            portal_redirect(($this->routeUrl)('admin/usuarios'));
        }
        portal_redirect(($this->routeUrl)($userId > 0 ? 'admin/usuarios/editar' : 'admin/usuarios/novo', $userId > 0 ? ['id' => $userId] : []));
    }

    public function usuariosExcluir(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $userId     = (int) ($_GET['id'] ?? 0);
        $saved      = $this->usersModel->delete($portalData, $userId);
        if ($saved !== $portalData) {
            portal_set_alert('success', 'Usuário removido com sucesso.');
        }
        portal_redirect(($this->routeUrl)('admin/usuarios'));
    }

    public function categoriasLista(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/categorias/lista.php', $this->baseViewData($portalData));
    }

    public function categoriasCadastro(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/categorias/cadastro.php', $this->baseViewData($portalData));
    }

    public function categoriasSalvar(): void
    {
        $this->requireAdmin();
        $portalData   = $this->repo->getPortalData();
        $originalSlug = trim((string) ($_POST['original_slug'] ?? ''));
        $saved        = $this->categoriesModel->save($portalData, $_POST);
        if ($saved !== $portalData) {
            portal_set_alert('success', $originalSlug !== '' ? 'Categoria atualizada com sucesso!' : 'Categoria salva com sucesso!');
        }
        portal_redirect(($this->routeUrl)('admin/categorias'));
    }

    public function categoriasExcluir(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $slug       = trim((string) ($_GET['slug'] ?? ''));
        $saved      = $this->categoriesModel->delete($portalData, $slug);
        if ($saved !== $portalData) {
            portal_set_alert('success', 'Categoria removida com sucesso.');
        }
        portal_redirect(($this->routeUrl)('admin/categorias'));
    }

    public function comentariosLista(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/comentarios/lista.php', $this->baseViewData($portalData));
    }

    public function comentariosStatus(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $commentId  = (int) ($_GET['id'] ?? 0);
        $newStatus  = trim((string) ($_GET['status'] ?? ''));
        $saved      = $this->commentsModel->setStatus($portalData, $commentId, $newStatus);
        if ($saved !== $portalData) {
            portal_set_alert('success', 'Status do comentário atualizado.');
        }
        portal_redirect(($this->routeUrl)('admin/comentarios'));
    }

    public function comentariosExcluir(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $commentId  = (int) ($_GET['id'] ?? 0);
        $saved      = $this->commentsModel->delete($portalData, $commentId);
        if ($saved !== $portalData) {
            portal_set_alert('success', 'Comentário removido com sucesso.');
        }
        portal_redirect(($this->routeUrl)('admin/comentarios'));
    }

    public function configuracoes(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $this->view->render('admin/configuracoes.php', $this->baseViewData($portalData));
    }

    public function configuracoesSalvar(): void
    {
        $this->requireAdmin();
        $portalData = $this->repo->getPortalData();
        $saved      = $this->settingsModel->save($portalData, $_POST);
        if ($saved !== $portalData) {
            portal_set_alert('success', 'Configurações atualizadas com sucesso!');
            portal_redirect(($this->routeUrl)('admin/configuracoes'));
        }
        portal_redirect(($this->routeUrl)('admin/configuracoes'));
    }
}
