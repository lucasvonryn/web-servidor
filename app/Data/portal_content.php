<?php

$portalSettings = [
    'nome_site' => 'O Editorial',
    'slogan' => 'Jornalismo com profundidade e compromisso.',
    'sobre' => 'O Editorial é um veículo jornalístico independente comprometido com a qualidade informativa e o pluralismo de ideias.',
    'itens_home' => 6,
    'show_featured' => true,
    'show_latest' => true,
    'show_categories' => true,
    'exibir_comentarios' => true,
    'contact_email' => 'contato@oeditorial.com.br',
    'footer_links' => 'Fale Conosco',
];

$portalCategories = [
    'tecnologia' => [
        'id' => 1,
        'slug' => 'tecnologia',
        'name' => 'Tecnologia',
        'tag_class' => 'tecnologia',
        'accent' => 'tech',
        'cover' => $assetUrl('assets/home/tecnologia-capa.png'),
        'description' => 'Inovação, IA, software e mundo digital.',
    ],
    'politica' => [
        'id' => 2,
        'slug' => 'politica',
        'name' => 'Política',
        'tag_class' => 'politica',
        'accent' => 'politica',
        'cover' => $assetUrl('assets/home/politica-capa.png'),
        'description' => 'Análises políticas nacionais e internacionais.',
    ],
    'ciencia' => [
        'id' => 3,
        'slug' => 'ciencia',
        'name' => 'Ciência',
        'tag_class' => 'ciencia',
        'accent' => 'ciencia',
        'cover' => $assetUrl('assets/home/ciencia-capa.png'),
        'description' => 'Descobertas científicas e pesquisas relevantes.',
    ],
    'meio-ambiente' => [
        'id' => 4,
        'slug' => 'meio-ambiente',
        'name' => 'Meio Ambiente',
        'tag_class' => 'meio-ambiente',
        'accent' => 'meio-ambiente',
        'cover' => $assetUrl('assets/home/meio-ambiente-capa.png'),
        'description' => 'Sustentabilidade, clima e natureza.',
    ],
    'cultura' => [
        'id' => 5,
        'slug' => 'cultura',
        'name' => 'Cultura',
        'tag_class' => 'cultura',
        'accent' => 'cultura',
        'cover' => $assetUrl('assets/home/cultura-capa.png'),
        'description' => 'Arte, literatura, música e comportamento.',
    ],
];

$portalUsers = [
    [
        'id' => 1,
        'nome' => 'Fernanda Pacheco',
        'email' => 'fernanda@oeditorial.com.br',
        'papel' => 'Administrador',
        'status' => 'Ativo',
        'created_at' => '14/01/2023',
    ],
    [
        'id' => 2,
        'nome' => 'Maria Beatriz',
        'email' => 'maria@oeditorial.com.br',
        'papel' => 'Editora',
        'status' => 'Ativo',
        'created_at' => '19/03/2023',
    ],
    [
        'id' => 3,
        'nome' => 'Lucas Gabriel',
        'email' => 'lucas@oeditorial.com.br',
        'papel' => 'Editor',
        'status' => 'Ativo',
        'created_at' => '09/04/2023',
    ],
    [
        'id' => 4,
        'nome' => 'Fernanda Lima',
        'email' => 'fernanda.lima@oeditorial.com.br',
        'papel' => 'Editor',
        'status' => 'Inativo',
        'created_at' => '04/06/2023',
    ],
];

$portalPosts = [
    [
        'id' => 1,
        'slug' => 'ia-mercado-trabalho',
        'title' => 'Inteligência Artificial transforma o mercado de trabalho global',
        'excerpt' => 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030, mas novas oportunidades surgem em dados, design de sistemas e supervisão de IA.',
        'content' => 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030. Especialistas apontam crescimento de novas funções em análise de dados, design de sistemas e supervisão de IA.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'author_short' => 'Ana',
        'date' => '17 de mar. de 2024',
        'status' => 'Publicado',
        'featured' => true,
        'cover' => $assetUrl('assets/home/tecnologia-capa.png'),
    ],
    [
        'id' => 2,
        'slug' => 'cupula-climatica-reducao-carbono',
        'title' => 'Cúpula climática define novas metas para redução de carbono',
        'excerpt' => 'Líderes de 195 países chegam a acordo histórico sobre energias limpas, preservação ambiental e metas de descarbonização mais rígidas para a próxima década.',
        'content' => 'Líderes de 195 países chegaram a um acordo histórico sobre energias limpas, preservação ambiental e metas de descarbonização mais rígidas para a próxima década.',
        'category' => 'meio-ambiente',
        'author' => 'Carla Eduardo Rocha',
        'author_short' => 'Carlos',
        'date' => '14 de mar. de 2024',
        'status' => 'Publicado',
        'featured' => true,
        'cover' => $assetUrl('assets/home/meio-ambiente-capa.png'),
    ],
    [
        'id' => 3,
        'slug' => 'reforma-tributaria-ampla-margem',
        'title' => 'Congresso aprova reforma tributária com ampla margem',
        'excerpt' => 'Aprovação histórica simplifica o sistema fiscal brasileiro, reorganiza tributos sobre consumo e pode impactar preços para consumidores e empresas no próximo ano.',
        'content' => 'Aprovação histórica simplifica o sistema fiscal brasileiro, reorganiza tributos sobre consumo e pode impactar preços para consumidores e empresas no próximo ano.',
        'category' => 'politica',
        'author' => 'Fernanda Lima',
        'author_short' => 'Fernanda',
        'date' => '11 de mar. de 2024',
        'status' => 'Publicado',
        'featured' => true,
        'cover' => $assetUrl('assets/home/politica-capa.png'),
    ],
    [
        'id' => 4,
        'slug' => 'molecula-envelhecimento-celular',
        'title' => 'Cientistas descobrem molécula capaz de reverter envelhecimento celular',
        'excerpt' => 'Pesquisa publicada na Nature revela composto que restaura função mitocondrial em células envelhecidas, abrindo caminho para novos estudos sobre longevidade e medicina regenerativa.',
        'content' => 'Pesquisa publicada na Nature revela composto que restaura função mitocondrial em células envelhecidas, abrindo caminho para novos estudos sobre longevidade e medicina regenerativa.',
        'category' => 'ciencia',
        'author' => 'Roberto Neves',
        'author_short' => 'Roberto',
        'date' => '9 de mar. de 2024',
        'status' => 'Publicado',
        'featured' => true,
        'cover' => $assetUrl('assets/home/ciencia-capa.png'),
    ],
    [
        'id' => 5,
        'slug' => 'arquitetura-urbana-espacos-publicos',
        'title' => 'Arquitetura urbana reinventa espaços públicos nas metrópoles',
        'excerpt' => 'Novas tendências de design transformam centros históricos em espaços verdes e culturais multifuncionais.',
        'content' => 'Novas tendências de design transformam centros históricos em espaços verdes, culturais e conviviais, reconectando a população com áreas antes degradadas.',
        'category' => 'cultura',
        'author' => 'Juliana Azevedo',
        'author_short' => 'Juliana',
        'date' => '7 de mar. de 2024',
        'status' => 'Publicado',
        'featured' => false,
        'cover' => $assetUrl('assets/home/cultura-capa.png'),
    ],
    [
        'id' => 6,
        'slug' => 'quantum-computing-fronteira-tecnologica',
        'title' => 'Quantum Computing: a próxima fronteira tecnológica',
        'excerpt' => 'Empresas de tecnologia aceleram investimentos em computação quântica, com aplicações esperadas para criptografia e descoberta de fármacos.',
        'content' => 'Empresas de tecnologia aceleram investimentos em computação quântica para resolver desafios complexos em simulação, segurança e processamento científico.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'author_short' => 'Ana',
        'date' => '4 de mar. de 2024',
        'status' => 'Publicado',
        'featured' => false,
        'cover' => $assetUrl('assets/home/tecnologia-capa.png'),
    ],
];

$portalComments = [
    [
        'id' => 1,
        'post_id' => 1,
        'autor' => 'Leitor O Editorial',
        'email' => 'leitor@oeditorial.com.br',
        'trecho' => 'Excelente análise sobre os impactos da IA no mercado.',
        'texto' => 'Excelente análise sobre os impactos da IA no mercado. Gostei da forma como a matéria apresentou riscos e oportunidades sem cair em alarmismo.',
        'status' => 'Aprovado',
        'data' => '18 de mar. de 2024',
    ],
    [
        'id' => 2,
        'post_id' => 2,
        'autor' => 'Paula Mendes',
        'email' => 'paula@email.com',
        'trecho' => 'Gostaria de ver mais dados sobre financiamento climático.',
        'texto' => 'Gostaria de ver mais dados sobre financiamento climático. Seria interessante incluir metas por continente e comparativos com acordos anteriores.',
        'status' => 'Pendente',
        'data' => '15 de mar. de 2024',
    ],
    [
        'id' => 3,
        'post_id' => 1,
        'autor' => 'Mariana Costa',
        'email' => 'mariana@email.com',
        'trecho' => 'Muito relevante. Gostaria de ver mais sobre quais profissões vão surgir.',
        'texto' => 'Muito relevante. Gostaria de ver mais sobre quais profissões vão surgir, não apenas as que serão extintas.',
        'status' => 'Aprovado',
        'data' => '18 de mar. de 2024',
    ],
    [
        'id' => 4,
        'post_id' => 1,
        'autor' => 'Anônimo123',
        'email' => 'anonimo@temp.com',
        'trecho' => 'Isso é alarmismo. A IA nunca vai substituir a criatividade humana.',
        'texto' => 'Isso é alarmismo. A IA nunca vai substituir a criatividade humana.',
        'status' => 'Rejeitado',
        'data' => '19 de mar. de 2024',
    ],
    [
        'id' => 5,
        'post_id' => 2,
        'autor' => 'Lucas Ferreira',
        'email' => 'lucas@email.com',
        'trecho' => 'Finalmente um acordo sério! Esperemos que os países realmente cumpram.',
        'texto' => 'Finalmente um acordo sério! Esperemos que os países realmente cumpram.',
        'status' => 'Aprovado',
        'data' => '15 de mar. de 2024',
    ],
    [
        'id' => 6,
        'post_id' => 3,
        'autor' => 'Pedro Almeida',
        'email' => 'pedro@email.com',
        'trecho' => 'A reforma precisava avançar, mas ainda quero ver a implementação prática.',
        'texto' => 'A reforma precisava avançar, mas ainda quero ver a implementação prática e seus reflexos no consumo.',
        'status' => 'Aprovado',
        'data' => '12 de mar. de 2024',
    ],
];

$categoryPostCounts = [];
foreach ($portalPosts as $post) {
    $slug = $post['category'];
    $categoryPostCounts[$slug] = ($categoryPostCounts[$slug] ?? 0) + 1;
}

foreach ($portalCategories as $slug => &$category) {
    $count = $categoryPostCounts[$slug] ?? 0;
    $category['count'] = $count;
    $category['count_label'] = $count . ' ' . ($count === 1 ? 'publicação' : 'publicações');
}
unset($category);

$portalFeaturedSlides = array_values(array_filter($portalPosts, static function (array $post): bool {
    return !empty($post['featured']);
}));

return [
    'settings' => $portalSettings,
    'categories' => $portalCategories,
    'posts' => $portalPosts,
    'users' => $portalUsers,
    'comments' => $portalComments,
    'featured_slides' => $portalFeaturedSlides,
];
