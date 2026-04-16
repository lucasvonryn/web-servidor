# Web Servidor — Portal Editorial em PHP

## Integrantes
- Lucas Gabriel
- Maria Beatriz
- Fernanda Pacheco

## Atribuições do grupo
- Lucas Gabriel: protótipo no Figma, escopo e estrutura geral do projeto
- Maria Beatriz: desenvolvimento do painel administrativo, CRUDs e organização visual do admin
- Fernanda Pacheco: desenvolvimento e design da área pública, design do painel admin e documentação

## Sobre o projeto
Este projeto foi desenvolvido para a disciplina de Desenvolvimento Web Servidor.

A aplicação consiste em um portal editorial em PHP 8+, com:
- área pública para navegação, leitura de publicações e autenticação de leitores
- área administrativa protegida por login
- formulários com tratamento no servidor
- organização baseada em MVC
- dados estáticos em vetores PHP, persistidos dinamicamente em sessão durante a execução

O protótipo visual das telas foi elaborado no Figma:

<https://www.figma.com/design/LlLAwxa2hDMQH0cUZGuxUr/Web-Servidor?node-id=1-5&t=dptCdPAwdHbfjG0m-1>

## Objetivos da aplicação
O sistema foi construído para demonstrar, na prática, os conteúdos trabalhados em aula:
- arrays
- inclusão de arquivos
- estruturação MVC
- formulários e requisições HTTP
- cookies e sessões

Além disso, o projeto contempla:
- autenticação com sessão
- área protegida para administração
- validações e feedback no lado do servidor
- CRUDs administrativos
- comentários em publicações
- filtros e paginação nas listagens do painel

## Arquitetura atual
O projeto adota uma estrutura MVC simples, sem framework PHP, adequada ao estágio atual do trabalho.

### Model
Responsável por regras de negócio e manipulação dos dados.

Arquivos principais:
- `app/Models/PortalRepository.php`
- `app/Models/PostsModel.php`
- `app/Models/CategoriesModel.php`
- `app/Models/UsersModel.php`
- `app/Models/CommentsModel.php`
- `app/Models/SettingsModel.php`

Os dados base do sistema ficam em:
- `app/Data/portal_content.php`

O repositório lê esse arquivo, normaliza os dados e mantém o estado corrente em `$_SESSION['portal_data']`, simulando persistência sem banco de dados nesta etapa.

### View
Responsável pela apresentação.

As views estão organizadas em:
- `app/Views/public`
- `app/Views/admin`
- `app/Views/partials`

Também fazem parte da camada de apresentação:
- `public/css/style.css`
- `public/assets/*`

### Controller
Responsável pelo fluxo das rotas, regras de acesso e coordenação entre models e views.

Arquivos principais:
- `app/Controllers/PublicController.php`
- `app/Controllers/AdminController.php`
- `app/Controllers/AuthController.php`

### Core
Arquivos responsáveis pelo funcionamento da aplicação:
- `app/Core/App.php`
- `app/Core/Router.php`
- `app/Core/View.php`
- `app/bootstrap.php`
- `public/index.php`

## Estrutura atual do projeto
```bash
web-servidor/
├── app/
│   ├── bootstrap.php
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── PublicController.php
│   ├── Core/
│   │   ├── App.php
│   │   ├── Router.php
│   │   └── View.php
│   ├── Data/
│   │   └── portal_content.php
│   ├── Models/
│   │   ├── CategoriesModel.php
│   │   ├── CommentsModel.php
│   │   ├── PortalRepository.php
│   │   ├── PostsModel.php
│   │   ├── SettingsModel.php
│   │   └── UsersModel.php
│   ├── Support/
│   │   └── portal_helpers.php
│   └── Views/
│       ├── admin/
│       ├── partials/
│       └── public/
├── docs/
│   ├── escopo.md
│   └── fluxo-do-projeto.md
├── public/
│   ├── assets/
│   ├── css/
│   │   └── style.css
│   ├── favicon.svg
│   └── index.php
└── README.md
```

## Fluxo de execução
Resumo do fluxo atual da aplicação:

1. Toda requisição entra por `public/index.php`
2. O arquivo carrega `app/bootstrap.php`
3. O bootstrap inicia a sessão, registra helpers, instancia models/controllers e cadastra as rotas
4. O `Router` associa rota + método HTTP ao controller correspondente
5. O controller processa a requisição
6. Os models validam e manipulam os dados
7. O `PortalRepository` persiste o estado na sessão
8. A view correspondente é renderizada

Existe uma explicação mais detalhada desse fluxo em:
- `docs/fluxo-do-projeto.md`

## Requisitos para execução
Para executar o projeto localmente, é necessário ter:
- PHP 8.0 ou superior
- navegador web

Nesta versão:
- não é necessário Composer
- não é necessário banco de dados
- não é necessário `.env`

## Instalação e execução
1. Clone o repositório:

```bash
git clone https://github.com/lucasvonryn/web-servidor.git
```

2. Acesse a pasta do projeto:

```bash
cd web-servidor
```

3. Inicie o servidor embutido do PHP apontando para `public`:

```bash
php -S localhost:8000 -t public
```

4. Abra no navegador:

```text
http://localhost:8000
```

Importante:
- não abra os arquivos `.php` diretamente com `file://`
- o projeto depende do servidor PHP para processar rotas, formulários, sessões e assets corretamente

## Rotas principais

### Área pública
- `http://localhost:8000/index.php?url=home`
- `http://localhost:8000/index.php?url=publicacoes`
- `http://localhost:8000/index.php?url=login`
- `http://localhost:8000/index.php?url=conta`

### Área administrativa
- `http://localhost:8000/index.php?url=admin/login`
- `http://localhost:8000/index.php?url=admin/posts`
- `http://localhost:8000/index.php?url=admin/usuarios`
- `http://localhost:8000/index.php?url=admin/categorias`
- `http://localhost:8000/index.php?url=admin/comentarios`
- `http://localhost:8000/index.php?url=admin/configuracoes`
- `http://localhost:8000/index.php?url=admin/logout`

## Credenciais de teste

### Administrador
- E-mail: `admin@admin.com`
- Senha: `123456`

### Usuário público
- E-mail: `leitor@oeditorial.com.br`
- Senha: `123456`

Também é possível criar uma conta pública pela tela de login/cadastro.

## Funcionamento dos dados estáticos
O sistema utiliza vetores estáticos como base de dados nesta etapa.

### Origem dos dados
Os dados iniciais do sistema ficam em:
- `app/Data/portal_content.php`

Esse arquivo contém registros base de:
- configurações
- categorias
- publicações
- usuários
- comentários

### Persistência temporária
Durante a execução:
1. o `PortalRepository` carrega os dados base
2. os dados são copiados para `$_SESSION['portal_data']`
3. os CRUDs alteram os dados da sessão
4. a interface pública e administrativa passa a refletir essas alterações

Assim, o projeto se comporta como um sistema dinâmico sem depender de banco de dados.

## Funcionalidades implementadas

### Área pública
- home com destaques, últimas publicações e categorias
- listagem geral de publicações
- listagem por categoria
- detalhe da publicação
- comentários para usuários autenticados
- login e cadastro de leitor
- área de conta do leitor
- logout do usuário público

### Painel administrativo
- login administrativo
- logout administrativo
- dashboard inicial
- CRUD de usuários
- CRUD de categorias
- CRUD de publicações
- moderação e exclusão de comentários
- configurações gerais do portal
- filtros e paginação nas listagens

## Formulários existentes
Além dos logins, o sistema possui diversos formulários com processamento no servidor:
- cadastro público
- comentário em publicação
- cadastro/edição de usuários
- cadastro/edição de categorias
- cadastro/edição de publicações
- configurações gerais do site

## Validações e feedback
O projeto utiliza validações no lado do servidor e feedback via sessão.

Exemplos implementados:
- obrigatoriedade de campos essenciais
- validação de e-mail
- senha mínima no cadastro público
- categoria válida ao salvar publicação
- autenticação obrigatória para comentar
- proteção das rotas administrativas
- bloqueio de exclusão de categoria com publicações vinculadas

O feedback ao usuário é feito com:
- `$_SESSION['alerta']`
- `$_SESSION['erros']`
- `$_SESSION['old']`
- `$_SESSION['erros_publico']`
- `$_SESSION['old_publico']`

## Sessões e autenticação
O sistema utiliza `$_SESSION` para:
- autenticação do administrador
- autenticação do usuário público
- persistência dinâmica dos dados
- mensagens de feedback
- repopulação de formulários após erro de validação

## Padronização e organização do código
Na versão atual, o projeto foi reorganizado para melhorar a apresentação e aderência aos requisitos da disciplina:
- separação de controllers, models, core, support e views
- centralização do bootstrap da aplicação
- uso de um front controller em `public/index.php`
- padronização de nomes de arquivos e diretórios
- uso de comentários no código para facilitar leitura e entendimento

## Arquivos mais importantes
- `public/index.php`
  Entrada única da aplicação.

- `app/bootstrap.php`
  Monta a aplicação, instancia dependências e registra as rotas.

- `app/Core/Router.php`
  Mapeia as rotas para handlers.

- `app/Core/App.php`
  Executa o dispatcher principal.

- `app/Controllers/*`
  Controlam fluxo, autenticação e integração entre model e view.

- `app/Models/PortalRepository.php`
  Gerencia dados estáticos + sessão.

- `app/Models/*Model.php`
  Regras de negócio por módulo.

- `app/Support/portal_helpers.php`
  Helpers reutilizáveis para redirect, alertas, guards e utilidades.

- `docs/fluxo-do-projeto.md`
  Documento de apoio para explicar a arquitetura.

## Limitações atuais
Por ser um protótipo acadêmico desta etapa:
- os dados ainda não são persistidos em banco de dados
- o estado é perdido quando a sessão é encerrada
- as credenciais de login são fixas para demonstração
- o sistema ainda pode evoluir em camadas futuras com persistência real

## Próximos passos possíveis
- migração dos vetores para banco de dados
- persistência real de usuários e comentários
- refinamento das regras de autenticação e permissões
- separação ainda maior de responsabilidades por controller/model

## Licença
Uso acadêmico. Projeto desenvolvido para a disciplina de Desenvolvimento Web Servidor.
