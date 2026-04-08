<?php

$portalCategories = [
    'tecnologia' => [
        'name' => 'Tecnologia',
        'tag_class' => 'tecnologia',
        'accent' => 'tech',
        'cover' => $assetUrl('assets/home/tecnologia-capa.png'),
        'description' => 'Inovação, IA, software e mundo digital.',
        'count_label' => '2 publicações',
    ],
    'politica' => [
        'name' => 'Política',
        'tag_class' => 'politica',
        'accent' => 'politics',
        'cover' => $assetUrl('assets/home/politica-capa.png'),
        'description' => 'Análises políticas nacionais e internacionais.',
        'count_label' => '1 publicação',
    ],
    'ciencia' => [
        'name' => 'Ciência',
        'tag_class' => 'ciencia',
        'accent' => 'science',
        'cover' => $assetUrl('assets/home/ciencia-capa.png'),
        'description' => 'Descobertas científicas e pesquisas relevantes.',
        'count_label' => '1 publicação',
    ],
    'meio-ambiente' => [
        'name' => 'Meio Ambiente',
        'tag_class' => 'meio-ambiente',
        'accent' => 'green',
        'cover' => $assetUrl('assets/home/meio-ambiente-capa.png'),
        'description' => 'Sustentabilidade, clima e natureza.',
        'count_label' => '1 publicação',
    ],
    'cultura' => [
        'name' => 'Cultura',
        'tag_class' => 'cultura',
        'accent' => 'culture',
        'cover' => $assetUrl('assets/home/cultura-capa.png'),
        'description' => 'Arte, literatura, música e comportamento.',
        'count_label' => '1 publicação',
    ],
];

$portalPosts = [
    [
        'slug' => 'ia-mercado-trabalho',
        'title' => 'Inteligência Artificial transforma o mercado de trabalho global',
        'excerpt' => 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030, mas novas oportunidades surgem em dados, design de sistemas e supervisão de IA.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'date' => '17 de mar. de 2024',
    ],
    [
        'slug' => 'cupula-climatica-reducao-carbono',
        'title' => 'Cúpula climática define novas metas para redução de carbono',
        'excerpt' => 'Líderes de 195 países chegam a acordo histórico sobre energias limpas, preservação ambiental e metas de descarbonização mais rígidas para a próxima década.',
        'category' => 'meio-ambiente',
        'author' => 'Carla Eduardo Rocha',
        'date' => '14 de mar. de 2024',
    ],
    [
        'slug' => 'reforma-tributaria-ampla-margem',
        'title' => 'Congresso aprova reforma tributária com ampla margem',
        'excerpt' => 'Aprovação histórica simplifica o sistema fiscal brasileiro, reorganiza tributos sobre consumo e pode impactar preços para consumidores e empresas no próximo ano.',
        'category' => 'politica',
        'author' => 'Fernanda Lima',
        'date' => '11 de mar. de 2024',
    ],
    [
        'slug' => 'molecula-envelhecimento-celular',
        'title' => 'Cientistas descobrem molécula capaz de reverter envelhecimento celular',
        'excerpt' => 'Pesquisa publicada na Nature revela composto que restaura função mitocondrial em células envelhecidas, abrindo caminho para novos estudos sobre longevidade e medicina regenerativa.',
        'category' => 'ciencia',
        'author' => 'Roberto Neves',
        'date' => '10 de mar. de 2024',
    ],
    [
        'slug' => 'arquitetura-urbana-espacos-publicos',
        'title' => 'Arquitetura urbana reinventa espaços públicos nas metrópoles',
        'excerpt' => 'Novas tendências de design transformam centros históricos em espaços verdes, culturais e conviviais, reconectando a população com áreas antes degradadas.',
        'category' => 'cultura',
        'author' => 'Juliana Azevedo',
        'date' => '7 de mar. de 2024',
    ],
    [
        'slug' => 'quantum-computing-fronteira-tecnologica',
        'title' => 'Quantum Computing: a próxima fronteira tecnológica',
        'excerpt' => 'Empresas de tecnologia aceleram investimentos em computação quântica para resolver desafios complexos em simulação, segurança e processamento científico.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'date' => '6 de mar. de 2024',
    ],
];

$portalFeaturedSlides = [
    $portalPosts[0],
    $portalPosts[1],
    $portalPosts[2],
    $portalPosts[3],
];

return [
    'categories' => $portalCategories,
    'posts' => $portalPosts,
    'featured_slides' => $portalFeaturedSlides,
];
