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
- persistência em banco MySQL via PDO
- scripts de modelagem e carga inicial do banco de dados

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

O arquivo de dados base foi mantido como referência histórica da primeira etapa. Na versão atual, o `PortalRepository` lê e grava os dados no banco MySQL usando PDO.

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
- `app/Core/Database.php`
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
│   │   ├── Database.php
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
│   ├── banco-de-dados.md
│   ├── escopo.md
│   ├── fluxo-do-projeto.md
│   └── instalacao.md
├── database/
│   ├── schema.sql
│   └── seed.sql
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
4. O `Database` cria a conexão PDO com o MySQL
5. O `Router` associa rota + método HTTP ao controller correspondente
6. O controller processa a requisição
7. Os models validam e manipulam os dados
8. O `PortalRepository` lê e persiste o estado no banco
9. A view correspondente é renderizada

Existe uma explicação mais detalhada desse fluxo em:
- `docs/fluxo-do-projeto.md`

Documentos complementares:
- `docs/instalacao.md`
- `docs/banco-de-dados.md`

## Requisitos para execução
Para executar o projeto localmente, é necessário ter:
- PHP 8.0 ou superior
- extensão `pdo_mysql` ativa no PHP
- MySQL 8.0 ou MariaDB compatível
- navegador web

Nesta versão:
- não é necessário Composer
- é necessário criar o banco MySQL antes de abrir a aplicação
- é recomendado criar um arquivo `.env` baseado em `.env.example`

## Instalação e execução
1. Clone o repositório:

```bash
git clone https://github.com/lucasvonryn/web-servidor.git
```

2. Acesse a pasta do projeto:

```bash
cd web-servidor
```

3. Confira se o PHP tem o driver MySQL do PDO:

```bash
php -m | grep pdo_mysql
```

Se não aparecer `pdo_mysql`, instale o pacote do PHP para MySQL:

```bash
sudo apt install php-mysql
```

4. Crie o banco MySQL:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p portal_editorial < database/seed.sql
```

5. Crie o arquivo `.env` a partir do exemplo:

```bash
cp .env.example .env
```

Atualize o `.env` se seu usuário/senha do MySQL forem diferentes:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_editorial
DB_USERNAME=root
DB_PASSWORD=
```

6. Inicie o servidor embutido do PHP apontando para `public`:

```bash
php -S localhost:8000 -t public
```

7. Abra no navegador:

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

## Funcionamento dos dados
O sistema agora utiliza MySQL como fonte principal dos dados.

### Origem dos dados iniciais
Os dados iniciais do sistema são inseridos por:
- `database/seed.sql`

Esse arquivo contém registros base de:
- configurações
- categorias
- publicações
- usuários
- comentários

### Persistência
Durante a execução:
1. o `Database` cria a conexão PDO
2. o `PortalRepository` consulta as tabelas do MySQL
3. os CRUDs alteram os dados por meio de prepared statements
4. a interface pública e administrativa passa a refletir os dados persistidos

Assim, o projeto passa a ter persistência real em banco de dados.

## Migração para MySQL
A segunda etapa do trabalho iniciou a troca da persistência em sessão por tabelas MySQL.

Arquivos criados:
- `database/schema.sql`
  Cria o banco `portal_editorial` e as tabelas `settings`, `users`, `categories`, `posts` e `comments`.

- `database/seed.sql`
  Insere os dados iniciais que antes estavam em `app/Data/portal_content.php`.

- `docs/banco-de-dados.md`
  Explica o mapeamento entre os dados da sessão e as tabelas do banco.

Para preparar o banco:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p portal_editorial < database/seed.sql
```

Nesta etapa, a estrutura de banco já está pronta e o `PortalRepository` já usa PDO para ler e gravar os dados.

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
  Gerencia a leitura e a escrita no MySQL usando PDO.

- `app/Models/*Model.php`
  Regras de negócio por módulo.

- `app/Support/portal_helpers.php`
  Helpers reutilizáveis para redirect, alertas, guards e utilidades.

- `docs/fluxo-do-projeto.md`
  Documento de apoio para explicar a arquitetura.

- `docs/instalacao.md`
  Guia de configuração local com PHP, MySQL, `.env` e scripts SQL.

- `docs/banco-de-dados.md`
  Manual do banco, tabelas, relacionamentos, PDO e segurança contra SQL Injection.

## Limitações atuais
Por ser um protótipo acadêmico desta etapa:
- o `PortalRepository` já usa PDO, mas a autenticação ainda pode ser refinada para usar usuários do banco
- as credenciais de login são fixas para demonstração
- o sistema ainda pode evoluir para models com consultas específicas por entidade

## Próximos passos possíveis
- autenticação usando usuários e senhas salvos no banco
- refinamento das regras de autenticação e permissões
- separação ainda maior de responsabilidades por controller/model

## Licença
Uso acadêmico. Projeto desenvolvido para a disciplina de Desenvolvimento Web Servidor.
