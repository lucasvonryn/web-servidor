<?php

require_once __DIR__ . '/PortalRepository.php';
require_once __DIR__ . '/../Support/portal_helpers.php';

class UsersModel
{
    private PortalRepository $repo;

    public function __construct(PortalRepository $repo)
    {
        $this->repo = $repo;
    }

    public function save(array $portalData, array $input): array
    {
        $userId = (int) ($input['id'] ?? 0);
        $nome   = trim((string) ($input['nome'] ?? ''));
        $email  = trim((string) ($input['email'] ?? ''));
        $papel  = trim((string) ($input['papel'] ?? 'editor'));
        $status = trim((string) ($input['status'] ?? 'Ativo'));
        $senha  = trim((string) ($input['senha'] ?? ''));

        if ($nome === '' || $email === '' || ($userId <= 0 && $senha === '')) {
            portal_set_alert('danger', 'Preencha nome, e-mail e senha para cadastrar o integrante.');
            return $portalData;
        }

        $users = $portalData['users'];
        if ($userId > 0) {
            foreach ($users as &$user) {
                if ((int) ($user['id'] ?? 0) === $userId) {
                    $user['nome']   = $nome;
                    $user['email']  = $email;
                    $user['papel']  = $papel === 'admin' ? 'Administrador' : 'Editor';
                    $user['status'] = $status === 'Inativo' ? 'Inativo' : 'Ativo';
                    break;
                }
            }
            unset($user);
        } else {
            $nextId = 1;
            foreach ($users as $user) {
                $nextId = max($nextId, (int) ($user['id'] ?? 0) + 1);
            }
            $users[] = [
                'id'         => $nextId,
                'nome'       => $nome,
                'email'      => $email,
                'papel'      => $papel === 'admin' ? 'Administrador' : 'Editor',
                'status'     => $status === 'Inativo' ? 'Inativo' : 'Ativo',
                'created_at' => date('d/m/Y'),
            ];
        }

        $portalData['users'] = $users;
        return $this->repo->persistPortalData($portalData);
    }

    public function delete(array $portalData, int $userId): array
    {
        $users             = $portalData['users'];
        $currentAdminEmail = trim((string) ($_SESSION['usuario_email'] ?? ''));

        foreach ($users as $index => $user) {
            if ((int) ($user['id'] ?? 0) !== $userId) {
                continue;
            }

            if ($currentAdminEmail !== '' && ($user['email'] ?? '') === $currentAdminEmail) {
                portal_set_alert('danger', 'Você não pode remover o usuário atualmente logado.');
                return $portalData;
            }

            unset($users[$index]);
            $portalData['users'] = array_values($users);
            return $this->repo->persistPortalData($portalData);
        }

        return $portalData;
    }
}
