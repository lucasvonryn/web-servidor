-- Carga inicial migrada de app/Data/portal_content.php
-- Execute este arquivo depois de database/schema.sql.

USE portal_editorial;

INSERT INTO settings (
    id,
    nome_site,
    slogan,
    about_text,
    itens_home,
    show_featured,
    show_latest,
    exibir_comentarios,
    contact_email,
    footer_links
) VALUES (
    1,
    'O Editorial',
    'Jornalismo com profundidade e compromisso.',
    'O Editorial é um veículo jornalístico independente comprometido com a qualidade informativa e o pluralismo de ideias.',
    6,
    1,
    1,
    1,
    'contato@oeditorial.com.br',
    'Fale Conosco'
);

INSERT INTO users (id, nome, email, senha_hash, papel, status, created_at) VALUES
(1, 'Fernanda Pacheco', 'fernanda@oeditorial.com.br', NULL, 'Administrador', 'Ativo', '2023-01-14'),
(2, 'Maria Beatriz', 'maria@oeditorial.com.br', NULL, 'Editora', 'Ativo', '2023-03-19'),
(3, 'Lucas Gabriel', 'lucas@oeditorial.com.br', NULL, 'Editor', 'Ativo', '2023-04-09'),
(4, 'Fernanda Lima', 'fernanda.lima@oeditorial.com.br', NULL, 'Editor', 'Inativo', '2023-06-04');

INSERT INTO categories (id, slug, name, tag_class, accent, cover, description) VALUES
(1, 'tecnologia', 'Tecnologia', 'tecnologia', 'tech', 'assets/home/tecnologia-capa.png', 'Inovação, IA, software e mundo digital.'),
(2, 'politica', 'Política', 'politica', 'politica', 'assets/home/politica-capa.png', 'Análises políticas nacionais e internacionais.'),
(3, 'ciencia', 'Ciência', 'ciencia', 'ciencia', 'assets/home/ciencia-capa.png', 'Descobertas científicas e pesquisas relevantes.'),
(4, 'meio-ambiente', 'Meio Ambiente', 'meio-ambiente', 'meio-ambiente', 'assets/home/meio-ambiente-capa.png', 'Sustentabilidade, clima e natureza.'),
(5, 'cultura', 'Cultura', 'cultura', 'cultura', 'assets/home/cultura-capa.png', 'Arte, literatura, música e comportamento.');

INSERT INTO posts (
    id,
    category_id,
    slug,
    title,
    excerpt,
    content,
    author,
    author_short,
    published_label,
    status,
    featured,
    cover
) VALUES
(1, 1, 'ia-mercado-trabalho', 'Inteligência Artificial transforma o mercado de trabalho global', 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030, mas novas oportunidades surgem em dados, design de sistemas e supervisão de IA.', 'Estudo revela que 40% das profissões serão impactadas por automação inteligente até 2030. Especialistas apontam crescimento de novas funções em análise de dados, design de sistemas e supervisão de IA.', 'Ana Beatriz Silva', 'Ana', '17 de mar. de 2024', 'Publicado', 1, 'assets/home/tecnologia-capa.png'),
(2, 4, 'cupula-climatica-reducao-carbono', 'Cúpula climática define novas metas para redução de carbono', 'Líderes de 195 países chegam a acordo histórico sobre energias limpas, preservação ambiental e metas de descarbonização mais rígidas para a próxima década.', 'Líderes de 195 países chegaram a um acordo histórico sobre energias limpas, preservação ambiental e metas de descarbonização mais rígidas para a próxima década.', 'Carla Eduardo Rocha', 'Carlos', '14 de mar. de 2024', 'Publicado', 1, 'assets/home/meio-ambiente-capa.png'),
(3, 2, 'reforma-tributaria-ampla-margem', 'Congresso aprova reforma tributária com ampla margem', 'Aprovação histórica simplifica o sistema fiscal brasileiro, reorganiza tributos sobre consumo e pode impactar preços para consumidores e empresas no próximo ano.', 'Aprovação histórica simplifica o sistema fiscal brasileiro, reorganiza tributos sobre consumo e pode impactar preços para consumidores e empresas no próximo ano.', 'Fernanda Lima', 'Fernanda', '11 de mar. de 2024', 'Publicado', 1, 'assets/home/politica-capa.png'),
(4, 3, 'molecula-envelhecimento-celular', 'Cientistas descobrem molécula capaz de reverter envelhecimento celular', 'Pesquisa publicada na Nature revela composto que restaura função mitocondrial em células envelhecidas, abrindo caminho para novos estudos sobre longevidade e medicina regenerativa.', 'Pesquisa publicada na Nature revela composto que restaura função mitocondrial em células envelhecidas, abrindo caminho para novos estudos sobre longevidade e medicina regenerativa.', 'Roberto Neves', 'Roberto', '9 de mar. de 2024', 'Publicado', 1, 'assets/home/ciencia-capa.png'),
(5, 5, 'arquitetura-urbana-espacos-publicos', 'Arquitetura urbana reinventa espaços públicos nas metrópoles', 'Novas tendências de design transformam centros históricos em espaços verdes e culturais multifuncionais.', 'Novas tendências de design transformam centros históricos em espaços verdes, culturais e conviviais, reconectando a população com áreas antes degradadas.', 'Juliana Azevedo', 'Juliana', '7 de mar. de 2024', 'Publicado', 0, 'assets/home/cultura-capa.png'),
(6, 1, 'quantum-computing-fronteira-tecnologica', 'Quantum Computing: a próxima fronteira tecnológica', 'Empresas de tecnologia aceleram investimentos em computação quântica, com aplicações esperadas para criptografia e descoberta de fármacos.', 'Empresas de tecnologia aceleram investimentos em computação quântica para resolver desafios complexos em simulação, segurança e processamento científico.', 'Ana Beatriz Silva', 'Ana', '4 de mar. de 2024', 'Publicado', 0, 'assets/home/tecnologia-capa.png');

INSERT INTO comments (id, post_id, autor, email, trecho, texto, status, published_label) VALUES
(1, 1, 'Leitor O Editorial', 'leitor@oeditorial.com.br', 'Excelente análise sobre os impactos da IA no mercado.', 'Excelente análise sobre os impactos da IA no mercado. Gostei da forma como a matéria apresentou riscos e oportunidades sem cair em alarmismo.', 'Aprovado', '18 de mar. de 2024'),
(2, 2, 'Paula Mendes', 'paula@email.com', 'Gostaria de ver mais dados sobre financiamento climático.', 'Gostaria de ver mais dados sobre financiamento climático. Seria interessante incluir metas por continente e comparativos com acordos anteriores.', 'Pendente', '15 de mar. de 2024'),
(3, 1, 'Mariana Costa', 'mariana@email.com', 'Muito relevante. Gostaria de ver mais sobre quais profissões vão surgir.', 'Muito relevante. Gostaria de ver mais sobre quais profissões vão surgir, não apenas as que serão extintas.', 'Aprovado', '18 de mar. de 2024'),
(4, 1, 'Anônimo123', 'anonimo@temp.com', 'Isso é alarmismo. A IA nunca vai substituir a criatividade humana.', 'Isso é alarmismo. A IA nunca vai substituir a criatividade humana.', 'Rejeitado', '19 de mar. de 2024'),
(5, 2, 'Lucas Ferreira', 'lucas@email.com', 'Finalmente um acordo sério! Esperemos que os países realmente cumpram.', 'Finalmente um acordo sério! Esperemos que os países realmente cumpram.', 'Aprovado', '15 de mar. de 2024'),
(6, 3, 'Pedro Almeida', 'pedro@email.com', 'A reforma precisava avançar, mas ainda quero ver a implementação prática.', 'A reforma precisava avançar, mas ainda quero ver a implementação prática e seus reflexos no consumo.', 'Aprovado', '12 de mar. de 2024');

ALTER TABLE users AUTO_INCREMENT = 5;
ALTER TABLE categories AUTO_INCREMENT = 6;
ALTER TABLE posts AUTO_INCREMENT = 7;
ALTER TABLE comments AUTO_INCREMENT = 7;
