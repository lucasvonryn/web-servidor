<?php

namespace App\Models;

class SettingsModel
{
    private PortalRepository $repo;

    public function __construct(PortalRepository $repo)
    {
        $this->repo = $repo;
    }

    public function save(array $portalData, array $input): array
    {
        $nomeSite     = trim((string) ($input['nome_site'] ?? ''));
        $slogan       = trim((string) ($input['slogan'] ?? ''));
        $aboutText    = trim((string) ($input['about_text'] ?? ''));
        $itensHome    = (int) ($input['itens_home'] ?? 6);
        $contactEmail = trim((string) ($input['contact_email'] ?? ''));

        if ($nomeSite === '') {
            portal_set_alert('danger', 'Erro: o nome do portal não pode estar vazio!');
            return $portalData;
        }

        $portalData['settings'] = array_merge($portalData['settings'], [
            'nome_site'          => $nomeSite,
            'slogan'             => $slogan,
            'about_text'         => $aboutText,
            'itens_home'         => in_array($itensHome, [5, 10, 20], true) ? $itensHome : 6,
            'show_featured'      => ! empty($input['show_featured']),
            'show_latest'        => ! empty($input['show_latest']),
            'exibir_comentarios' => ! empty($input['exibir_comentarios']),
            'contact_email'      => $contactEmail !== '' ? $contactEmail : ($portalData['settings']['contact_email'] ?? 'contato@oeditorial.com.br'),
        ]);

        return $this->repo->persistPortalData($portalData);
    }
}
