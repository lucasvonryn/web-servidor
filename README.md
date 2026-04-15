# Web Servidor — Portal Editorial em PHP

## Integrantes
- Lucas Gabriel
- Maria Beatriz
- Fernanda Pacheco

## Atribuições aos integrantes
- Lucas Gabriel: Protótipo no figma e lógica de estrutura do projeto
- Maria Beatriz: Desenvolvimento do painel administrativo, CRUD de categorias e publicações
- Fernanda Pacheco: Desenvolvimento da área pública, autenticação, formulários e validações

## Sobre o projeto
Este projeto foi desenvolvido para a disciplina de Desenvolvimento Web Servidor.

A aplicação consiste em um portal editorial em PHP 8+, com área pública para navegação e leitura de publicações e uma área administrativa protegida por autenticação para gerenciamento de usuários, categorias, publicações, comentários e configurações gerais do site.

O protótipo visual das telas foi elaborado no Figma:

<https://www.figma.com/design/LlLAwxa2hDMQH0cUZGuxUr/Web-Servidor?node-id=1-5&t=dptCdPAwdHbfjG0m-1>

## Objetivos do sistema
O sistema foi pensado para demonstrar os conceitos trabalhados em aula:
- arrays
- inclusão de arquivos
- estrutura inspirada em MVC
- formulários e requisições HTTP
- cookies e sessões

Além disso, o projeto contempla:
- autenticação para área protegida
- formulários com validação no servidor
- mensagens de feedback ao usuário
- listagens administrativas com filtros e paginação
- persistência temporária dos dados em sessão, a partir de vetores estáticos

## Arquitetura adotada
O projeto adota uma organização inspirada no padrão MVC, adequada ao estágio atual do trabalho e requisitada em seu enunciado.

### Model
Os dados base do sistema ficam centralizados em vetores PHP no arquivo:
- `app/Data/portal_content.php`

Esse arquivo funciona como a camada de dados do protótipo por meio de um vetor dinâmico. A partir dele, os registros são carregados e copiados para a sessão, permitindo operações de cadastro, edição e exclusão sem necessidade de banco de dados nesta etapa.

### Controller
O ponto de entrada da aplicação está em:
- `public/index.php`

Esse arquivo atua como controlador frontal da aplicação. Ele é responsável por:
- receber a rota via `$_GET['url']`
- processar formulários enviados por `POST`
- validar dados no servidor
- controlar autenticação com sessão
- atualizar os vetores dinâmicos em `$_SESSION`
- encaminhar para as views correspondentes

### View
As telas ficam organizadas em:
- `app/Views/public`
Conjunto de telas públicas, acessíveis aos visitantes e leitores cadastrados.
- `app/Views/admin`
Parte do painel administrativo, protegido por autenticação.
- `app/Views/partials`
Reutilizáveis no frontend, como header e footer.

As views são responsáveis apenas pela apresentação do conteúdo, estrutura HTML e integração com os estilos do frontend.

## Estrutura atual do projeto
```bash
web-servidor/
├── app/
│   ├── Data/
│   │   └── portal_content.php
│   └── Views/
│       ├── admin/
│       │   ├── categorias/
│       │   ├── comentarios/
│       │   ├── posts/
│       │   ├── usuarios/
│       │   ├── configuracoes.php
│       │   ├── login.php
│       │   └── painel.php
│       ├── partials/
│       │   ├── footer.php
│       │   └── header.php
│       └── public/
│           ├── assets/
│           ├── categoria.php
│           ├── conta.php
│           ├── home.php
│           ├── login.php
│           ├── publicacao.php
│           └── publicacoes.php
├── docs/
│   └── escopo.md
├── public/
│   ├── assets/
│   │   └── home/
│   ├── css/
│   │   └── style.css
│   └── index.php
└── README.md
```

## Requisitos para execução
Para executar o projeto localmente, é necessário ter:
- PHP 8.0 ou superior
- navegador web

Nesta versão do trabalho:
- não é necessário Composer
- não é necessário banco de dados
- não é necessário configurar `.env`

## Instalação e execução
1. Clone o repositório:

```bash
git clone <https://github.com/lucasvonryn/web-servidor.git>
```

2. Acesse a pasta do projeto:

```bash
cd web-servidor
```

3. Inicie o servidor embutido do PHP apontando para a pasta `public`:

```bash
php -S localhost:8000 -t public
```

4. Abra no navegador:

```text
http://localhost:8000
```

Importante:
- não abra os arquivos `.php` diretamente no navegador com `file://`
- a aplicação depende do servidor PHP local para processar rotas, formulários, sessões e carregamento correto de assets

## Rotas principais

### Área pública
- `http://localhost:8000/index.php?url=home`
- `http://localhost:8000/index.php?url=publicacoes`
- `http://localhost:8000/index.php?url=login`

### Área administrativa
- `http://localhost:8000/index.php?url=admin/login`
- `http://localhost:8000/index.php?url=admin/painel`
- `http://localhost:8000/index.php?url=admin/usuarios`
- `http://localhost:8000/index.php?url=admin/categorias`
- `http://localhost:8000/index.php?url=admin/posts`
- `http://localhost:8000/index.php?url=admin/comentarios`
- `http://localhost:8000/index.php?url=admin/configuracoes`

## Credenciais de teste

### Administrador
- E-mail: `admin@admin.com`
- Senha: `123456`

### Usuário público
- E-mail: `leitor@oeditorial.com.br`
- Senha: `123456`

Também é possível criar uma conta pública pela tela de login/cadastro do portal.

## Funcionamento dos dados
O sistema utiliza uma abordagem híbrida para esta etapa do trabalho:

1. O arquivo `app/Data/portal_content.php` contém os vetores base do sistema, como categorias pré criadas, publicações, usuários e comentários.
2. Ao carregar a aplicação, esses dados são lidos e normalizados.
3. Os dados são copiados para `$_SESSION['portal_data']`.
4. As operações do painel administrativo atualizam essa versão em sessão.

Com isso, o projeto consegue simular comportamento dinâmico sem banco de dados, mantendo consistência entre:
- área pública
- painel administrativo
- autenticação
- comentários
- configurações

## Funcionalidades implementadas

### Área pública
- home com destaques, últimas publicações e categorias
- listagem de publicações
- listagem por categoria
- detalhe completo da publicação e visualização de comentários
- login e cadastro de usuário público
- área de conta do leitor
- logout de usuário público

### Painel administrativo
- login administrativo
- painel inicial com métricas
- CRUD de usuários da equipe
- CRUD de categorias
- CRUD de publicações
- moderação e exclusão de comentários
- configurações gerais do site
- filtros e paginação nas listagens

## Formulários presentes no sistema
O sistema possui vários formulários processados no servidor além de login administrativo e público (os quais não são contatos na avaliação), entre eles:
- cadastro público
- cadastro/edição de usuários
- cadastro/edição de categorias
- cadastro/edição de publicações
- formulário de comentários
- formulário de configurações

As validações principais são feitas no lado do servidor, no arquivo controlador `public/index.php`.

## Validações e feedback ao usuário
O projeto utiliza:
- validações em PHP antes de salvar ou autenticar
- redirecionamento após processamento
- mensagens de sucesso e erro em sessão
- feedback visual nas telas

Exemplos de regras implementadas:
- obrigatoriedade de campos essenciais
- validação de e-mail
- verificação de senha mínima no cadastro público
- categoria válida ao salvar publicação
- proteção de rotas administrativas por sessão
- bloqueio de comentário para usuário não autenticado
- bloqueio de exclusão de categoria com publicações vinculadas

## Sessões e autenticação
O projeto utiliza `$_SESSION` para:
- autenticação do administrador
- autenticação do usuário público
- armazenamento temporário dos dados do portal
- exibição de mensagens de feedback

Isso atende ao requisito de autenticação com área protegida utilizando sessão.

## Arquivos importantes
- `public/index.php`
  Controlador frontal, roteamento, validações, autenticação e persistência em sessão.

- `app/Data/portal_content.php`
  Vetores base da aplicação.

- `app/Views/public/*`
  Telas públicas do portal.

- `app/Views/admin/*`
  Telas do painel administrativo.

- `app/Views/partials/header.php`
  Cabeçalho compartilhado entre as telas.

- `app/Views/partials/footer.php`
  Rodapé compartilhado e scripts auxiliares.

- `public/css/style.css`
  Estilos gerais do frontend e do painel administrativo.

## Limitações atuais
Esta versão do projeto ainda possui limitações próprias de um protótipo acadêmico:
- os dados não são persistidos em banco de dados
- os dados são reiniciados quando a sessão é perdida
- a estrutura está organizada de forma compatível com MVC, mas ainda pode evoluir para separar controllers e models em arquivos próprios
- o login utiliza credenciais fixas para fins de demonstração

## Próximos passos
Evoluções previstas para versões futuras:
- migração dos vetores em sessão para banco de dados
- separação explícita de controllers e models em diretórios próprios
- criação de camada de configuração
- melhoria do controle de permissões
- persistência real de usuários públicos e administrativos
