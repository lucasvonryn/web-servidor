<?php
$categories = [
    'tecnologia' => [
        'name' => 'Tecnologia',
        'tag_class' => 'tecnologia',
        'accent' => 'tech',
        'cover' => $assetUrl('assets/home/tecnologia-capa.png'),
        'description' => 'Inovação, IA, software e mundo digital.',
        'count' => '12 publicações',
    ],
    'politica' => [
        'name' => 'Política',
        'tag_class' => 'politica',
        'accent' => 'politics',
        'cover' => $assetUrl('assets/home/politica-capa.png'),
        'description' => 'Análises políticas nacionais e internacionais.',
        'count' => '8 publicações',
    ],
    'ciencia' => [
        'name' => 'Ciência',
        'tag_class' => 'ciencia',
        'accent' => 'science',
        'cover' => $assetUrl('assets/home/ciencia-capa.png'),
        'description' => 'Descobertas científicas e pesquisas relevantes.',
        'count' => '5 publicações',
    ],
    'meio-ambiente' => [
        'name' => 'Meio Ambiente',
        'tag_class' => 'meio-ambiente',
        'accent' => 'green',
        'cover' => $assetUrl('assets/home/meio-ambiente-capa.png'),
        'description' => 'Sustentabilidade, clima e natureza.',
        'count' => '6 publicações',
    ],
    'cultura' => [
        'name' => 'Cultura',
        'tag_class' => 'cultura',
        'accent' => 'culture',
        'cover' => $assetUrl('assets/home/cultura-capa.png'),
        'description' => 'Arte, literatura, música e comportamento.',
        'count' => '10 publicações',
    ],
];

$featuredPosts = [
    [
        'title' => 'Inteligência Artificial transforma o mercado de trabalho global',
        'excerpt' => 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030, mas novas oportunidades surgem em análise de dados, produto digital e supervisão de sistemas autônomos.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'date' => '17 de mar. de 2024',
        'size' => 'large',
    ],
    [
        'title' => 'Cúpula climática define novas metas para redução de carbono',
        'excerpt' => 'Líderes de 195 países firmam acordo histórico para acelerar a transição energética, proteger biomas e ampliar o financiamento verde em países mais vulneráveis.',
        'category' => 'meio-ambiente',
        'author' => 'Carla Eduardo Rocha',
        'date' => '14 de mar. de 2024',
        'size' => 'small',
    ],
];

$latestPosts = [
    [
        'title' => 'Inteligência Artificial transforma o mercado de trabalho global',
        'excerpt' => 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030, mas novas oportunidades surgem em dados, design de sistemas e supervisão de IA.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'date' => '17 de mar. de 2024',
    ],
    [
        'title' => 'Cúpula climática define novas metas para redução de carbono',
        'excerpt' => 'Líderes de 195 países chegam a acordo histórico sobre energias limpas, preservação ambiental e metas de descarbonização mais rígidas para a próxima década.',
        'category' => 'meio-ambiente',
        'author' => 'Carla Eduardo Rocha',
        'date' => '14 de mar. de 2024',
    ],
    [
        'title' => 'Congresso aprova reforma tributária com ampla margem',
        'excerpt' => 'Aprovação histórica simplifica o sistema fiscal brasileiro, reorganiza tributos sobre consumo e pode impactar preços para consumidores e empresas no próximo ano.',
        'category' => 'politica',
        'author' => 'Fernanda Lima',
        'date' => '11 de mar. de 2024',
    ],
    [
        'title' => 'Cientistas descobrem molécula capaz de reverter envelhecimento celular',
        'excerpt' => 'Pesquisa publicada na Nature revela composto que restaura função mitocondrial em células envelhecidas, abrindo caminho para novos estudos sobre longevidade e medicina regenerativa.',
        'category' => 'ciencia',
        'author' => 'Roberto Neves',
        'date' => '10 de mar. de 2024',
    ],
    [
        'title' => 'Arquitetura urbana reinventa espaços públicos nas metrópoles',
        'excerpt' => 'Novas tendências de design transformam centros históricos em espaços verdes, culturais e conviviais, reconectando a população com áreas antes degradadas.',
        'category' => 'cultura',
        'author' => 'Juliana Azevedo',
        'date' => '7 de mar. de 2024',
    ],
    [
        'title' => 'Quantum Computing: a próxima fronteira tecnológica',
        'excerpt' => 'Empresas de tecnologia aceleram investimentos em computação quântica para resolver desafios complexos em simulação, segurança e processamento científico.',
        'category' => 'tecnologia',
        'author' => 'Ana Beatriz Silva',
        'date' => '6 de mar. de 2024',
    ],
];

include __DIR__ . '/../partials/header.php';
?>

<div class="portal-home">
    <section class="hero-home">
        <div class="container">
            <div class="hero-home-panel">
                <div class="hero-home-grid">
                    <?php foreach ($featuredPosts as $post): ?>
                        <?php $category = $categories[$post['category']]; ?>
                        <article id="<?= htmlspecialchars($post['category']) ?>" class="featured-card featured-card-<?= htmlspecialchars($post['size']) ?>">
                            <div class="featured-card-media">
                                <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                            </div>
                            <div class="featured-card-overlay">
                                <span class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </span>
                                <h2><?= htmlspecialchars($post['title']) ?></h2>
                                <p><?= htmlspecialchars($post['excerpt']) ?></p>
                                <div class="post-meta">
                                    <span><?= htmlspecialchars($post['author']) ?></span>
                                    <span><?= htmlspecialchars($post['date']) ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="headline-strip">
        <div class="container">
            <span class="headline-label">Destaques</span>
            <div class="headline-track">
                <span>60 novos: reforma tributária com ampla margem</span>
                <span>Cientistas descobrem molécula capaz de reverter envelhecimento celular</span>
                <span>Arquitetura urbana reinventa espaços públicos nas metrópoles</span>
            </div>
        </div>
    </section>

    <section class="latest-section">
        <div class="container">
            <div class="section-heading">
                <h3>Últimas Publicações</h3>
                <a href="<?= htmlspecialchars($routeUrl('home')) ?>#todas">Ver todas</a>
            </div>

            <div class="post-grid" id="todas">
                <?php foreach ($latestPosts as $post): ?>
                    <?php $category = $categories[$post['category']]; ?>
                    <article id="<?= htmlspecialchars($post['category']) ?>-card" class="news-card">
                        <div class="news-card-media">
                            <img src="<?= htmlspecialchars($category['cover']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                        </div>
                        <div class="news-card-body">
                            <span class="post-tag post-tag-<?= htmlspecialchars($category['tag_class']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </span>
                            <h4><?= htmlspecialchars($post['title']) ?></h4>
                            <p><?= htmlspecialchars($post['excerpt']) ?></p>
                            <div class="post-meta">
                                <span><?= htmlspecialchars($post['author']) ?></span>
                                <span><?= htmlspecialchars($post['date']) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="categories-section">
        <div class="container">
            <div class="section-heading">
                <h3>Explore por Categoria</h3>
            </div>

            <div class="category-grid">
                <?php foreach ($categories as $slug => $category): ?>
                    <a id="<?= htmlspecialchars($slug) ?>-categoria" class="category-card accent-<?= htmlspecialchars($category['accent']) ?>" href="<?= htmlspecialchars($routeUrl('home')) ?>#<?= htmlspecialchars($slug) ?>">
                        <span class="category-name"><?= htmlspecialchars($category['name']) ?></span>
                        <p><?= htmlspecialchars($category['description']) ?></p>
                        <div class="category-meta">
                            <span><?= htmlspecialchars($category['count']) ?></span>
                            <span>&rarr;</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
