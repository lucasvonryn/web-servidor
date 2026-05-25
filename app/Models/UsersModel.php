<?php

namespace App\Models;

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

        $users = $portalData['users'] ?? [];

       
        foreach ($users as $user) {
            if ((int)($user['id'] ?? 0) !== $userId && strtolower(trim($user['email'] ?? '')) === strtolower($email)) {
                portal_set_alert('danger', 'Este e-mail já está cadastrado para outro membro da equipe.');
                return $portalData;
            }
        }

        if ($userId > 0) {
            foreach ($users as &$user) {
                if ((int) ($user['id'] ?? 0) === $userId) {
                    $user['nome']   = $nome;
                    $user['email']  = $email;
                    $user['papel']  = $papel === 'admin' ? 'Administrador' : 'Editor';
                    $user['status'] = $status === 'Inativo' ? 'Inativo' : 'Ativo';
                    
                    
                    if ($senha !== '') {
                        $user['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
                        $user['senha']      = $user['senha_hash']; 
                    }
                    break;
                }
            }
            unset($user);
        } else {
            $nextId = 1;
            foreach ($users as $user) {
                $nextId = max($nextId, (int) ($user['id'] ?? 0) + 1);
            }

            
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $users[] = [
                'id'         => $nextId,
                'nome'       => $nome,
                'email'      => $email,
                'senha_hash' => $senhaHash, 
                'senha'      => $senhaHash, 
                'papel'      => $papel === 'admin' ? 'Administrador' : 'Editor',
                'status'     => $status === 'Inativo' ? 'Inativo' : 'Ativo',
                'created_at' => date('Y-m-d'), 
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
    public function emailExists(array $portalData, string $email): bool
    {
        $users = $portalData['users'] ?? [];
        foreach ($users as $user) {
            if (strcasecmp(trim((string)($user['email'] ?? '')), trim($email)) === 0) {
                return true;
            }
        }
        return false;
    }


    public function createPublicUser(array $portalData, array $input): array
    {
        $nome  = trim((string) ($input['nome'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $senha = trim((string) ($input['senha'] ?? ''));

        
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $users = $portalData['users'] ?? [];
        
        
        $nextId = 1;
        foreach ($users as $user) {
            $nextId = max($nextId, (int) ($user['id'] ?? 0) + 1);
        }

       
        $newUser = [
            'id'         => $nextId,
            'nome'       => $nome,
            'email'      => $email,
            'senha'      => $senhaHash,
            'senha_hash' => $senhaHash,
            'papel'      => 'Editor', 
            'status'     => 'Ativo',
            'created_at' => date('d/m/Y'),
        ];

        $users[] = $newUser;
        $portalData['users'] = $users;

        
        $updatedData = $this->repo->persistPortalData($portalData);
        
        
        $updatedData['last_inserted_id'] = $nextId;

        return $updatedData;
    }
  
    public function findByEmail(array $portalData, string $email): ?array
    {
        $users = $portalData['users'] ?? [];
        foreach ($users as $user) {
            if (strcasecmp(trim((string)($user['email'] ?? '')), trim($email)) === 0) {
                return $user;
            }
        }
        return null;
    }
}
