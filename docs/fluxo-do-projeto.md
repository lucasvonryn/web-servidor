# Fluxo completo do projeto (visão “do clique até salvar”)

Este documento explica **todo o caminho de execução** do sistema: da URL acessada até a renderização, e como acontecem **criação/edição/exclusão** com persistência em MySQL via PDO.

---

## 1) Entrada única (Front Controller)

- **Arquivo**: `public/index.php`

Toda requisição entra por um único arquivo. Ele carrega o bootstrap do app e executa o dispatcher.

Em resumo:

- recebe a rota pelo query string (`$_GET['url']`)
- inicializa a aplicação via `app/bootstrap.php`
- chama `$app->run()`

---

## 2) Bootstrap (montagem e dependências)

- **Arquivo**: `app/bootstrap.php`

O bootstrap é responsável por “montar” a aplicação:

- inicia a sessão com `session_start()` (essencial para login e mensagens)
- habilita `error_reporting` durante desenvolvimento
- calcula o `basePath` (permite o projeto funcionar em subpasta)
- cria helpers:
  - `routeUrl($rota, $params)` para gerar links internos
  - `assetUrl($path)` para gerar links para CSS/JS/imagens
- carrega classes e helpers do projeto
- instancia:
  - `Database` (conexão PDO com MySQL)
  - `PortalRepository` (camada de dados no banco)
  - Models por módulo (`PostsModel`, `CategoriesModel`, etc.)
  - Controllers (`PublicController`, `AdminController`, `AuthController`)
  - `Router` e `View`
- registra todas as rotas (GET/POST) no `Router`
- cria e retorna `App`

---

## 3) Router (mapeia rota → handler)

- **Arquivo**: `app/Core/Router.php`

O `Router` funciona como um mapa:

- rota + método HTTP → função/callback que será executado

Exemplos (simplificados):

- `GET admin/posts` → `AdminController->postsLista()`
- `POST admin/posts/salvar` → `AdminController->postsSalvar()`
- `POST processar-login` → `AuthController->loginAdmin()`

---

## 4) App (dispatcher)

- **Arquivo**: `app/Core/App.php`

O `App` é quem executa de fato o fluxo:

- lê a rota atual de `$_GET['url']` (com fallback, geralmente `home`)
- identifica o método HTTP (`GET` ou `POST`)
- pede para o `Router` executar o handler correspondente
- se a rota não existir, responde 404

---

## 5) Controllers (controle, validação e redirects)

- **Arquivos**: `app/Controllers/*`

Controllers são a camada de controle. Eles:

- validam dados recebidos (ou delegam ao Model)
- protegem rotas (admin logado, usuário público logado)
- chamam models para alterar dados
- registram feedback em sessão (sucesso/erro)
- fazem redirect após POST (padrão PRG: Post → Redirect → Get)

### 5.1) Admin: exemplo de “salvar publicação”

- rota: `POST admin/posts/salvar`
- handler: `AdminController->postsSalvar()`

Fluxo típico:

1. `portal_require_admin(...)` garante que o admin está logado
2. `PostsModel->validate(...)` verifica campos obrigatórios e regras
3. se houver erro:
   - salva em sessão:
     - `$_SESSION['erros']` (mensagens por campo)
     - `$_SESSION['old']` (para repopular formulário)
   - redireciona de volta para `admin/posts/novo` ou `admin/posts/editar`
4. se estiver OK:
   - `PostsModel->save(...)` altera os dados (via repositório)
   - `portal_set_alert('success', ...)` para feedback
   - redireciona para `admin/posts` (listagem)

### 5.2) Auth: login do admin

- rota: `POST processar-login`
- handler: `AuthController->loginAdmin()`

Fluxo:

1. confere credenciais
2. se OK:
   - seta `$_SESSION['usuario_logado'] = true`
   - seta nome/e-mail do usuário
   - redireciona para o admin
3. se falhar:
   - preenche erros/old em sessão
   - redireciona para `admin/login`

---

## 6) Models (regras de negócio por módulo)

- **Arquivos**: `app/Models/*Model.php`

Cada Model encapsula regras e operações do seu módulo:

- validação dos campos (servidor)
- criação/edição/exclusão
- regras simples (ex.: impedir excluir categoria com posts vinculados, etc.)

Eles trabalham com arrays compatíveis com as views e usam o repositório para obter/persistir dados no banco.

---

## 7) PortalRepository (persistência em MySQL)

- **Arquivo**: `app/Models/PortalRepository.php`

O repositório faz o papel de camada de dados:

- lê as tabelas MySQL via PDO
- monta o array `portalData` esperado pelas views
- grava alterações no banco usando prepared statements

Como funciona:

1. Ao carregar a aplicação, `Database::connect()` cria a conexão PDO
2. O repositório consulta `settings`, `users`, `categories`, `posts` e `comments`
3. Nas operações de CRUD, os Models alteram o array em memória
4. O repositório grava o estado atualizado no banco

Consequência:

- os dados persistem mesmo após fechar o navegador
- o sistema deixa de depender de `$_SESSION['portal_data']` para manter publicações, categorias, usuários e comentários

---

## 8) Views (apresentação)

- **Arquivos**: `app/Views/**`

As views são a camada de apresentação (HTML + PHP simples). O controller chama:

- `View->render('caminho/da/view.php', $data)`

Estrutura:

- `app/Views/public/*` → telas públicas
- `app/Views/admin/*` → painel administrativo
- `app/Views/partials/*` → header/footer reutilizáveis

`partials/header.php` monta o layout e navegação, e `partials/footer.php` contém scripts auxiliares (ex.: busca/paginação do admin).

---

## 9) Feedback e erros (sessão)

O projeto usa sessão para:

- alertas globais de sucesso/erro: `$_SESSION['alerta']`
- erros por campo: `$_SESSION['erros']` / `$_SESSION['erros_publico']`
- repopular valores: `$_SESSION['old']` / `$_SESSION['old_publico']`

Esse padrão permite:

- validar no servidor
- redirecionar após POST
- exibir feedback na próxima tela
