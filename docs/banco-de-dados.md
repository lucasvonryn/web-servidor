# Manual do Banco de Dados

Este documento descreve a modelagem MySQL do Portal Editorial e a migração da persistência temporária em sessão para tabelas relacionais acessadas via PDO.

## Objetivo

Na primeira etapa do projeto, os dados eram carregados de `app/Data/portal_content.php` e alterados temporariamente em `$_SESSION['portal_data']`.

Na segunda etapa, esses dados passaram a ser persistidos no banco `portal_editorial`, criado em `database/schema.sql` e populado inicialmente por `database/seed.sql`.

## Arquivos

- `database/schema.sql`: cria o banco, remove tabelas antigas e recria a estrutura.
- `database/seed.sql`: insere os dados iniciais do portal.
- `app/Core/Database.php`: cria a conexão PDO com MySQL.
- `app/Models/PortalRepository.php`: lê e grava dados no banco.
- `.env.example`: modelo das variáveis de conexão.

## Tabelas

| Tabela | Finalidade |
| --- | --- |
| `settings` | Configurações gerais do portal |
| `users` | Usuários administrativos, editores e leitores |
| `categories` | Categorias editoriais |
| `posts` | Publicações do portal |
| `comments` | Comentários vinculados às publicações |

## Campos Principais

### `settings`

Armazena uma única linha de configuração geral do site.

Campos principais:
- `id`: chave primária fixa com valor `1`.
- `nome_site`: nome exibido no portal.
- `slogan`: frase institucional.
- `about_text`: texto usado no rodapé/sobre.
- `itens_home`: quantidade de publicações na home.
- `show_featured`: controla o bloco de destaques.
- `show_latest`: controla últimas publicações.
- `exibir_comentarios`: ativa/desativa comentários.
- `contact_email`: e-mail público de contato.

### `users`

Armazena pessoas cadastradas no sistema.

Campos principais:
- `id`: chave primária.
- `nome`: nome do usuário.
- `email`: e-mail único.
- `senha_hash`: espaço reservado para senha criptografada.
- `papel`: `Administrador`, `Editora`, `Editor` ou `Leitor`.
- `status`: `Ativo` ou `Inativo`.
- `created_at`: data de criação.

### `categories`

Armazena as editorias do portal.

Campos principais:
- `id`: chave primária.
- `slug`: identificador usado nas URLs.
- `name`: nome da categoria.
- `tag_class`: classe visual usada na interface.
- `accent`: variação visual da categoria.
- `cover`: imagem de capa.
- `description`: descrição da editoria.

### `posts`

Armazena as publicações.

Campos principais:
- `id`: chave primária.
- `category_id`: chave estrangeira para `categories.id`.
- `slug`: identificador único da publicação.
- `title`: título.
- `excerpt`: resumo.
- `content`: conteúdo.
- `author`: autor.
- `published_label`: data textual exibida na interface.
- `status`: `Publicado` ou `Rascunho`.
- `featured`: marca publicação em destaque.
- `cover`: imagem de capa.

### `comments`

Armazena comentários dos leitores.

Campos principais:
- `id`: chave primária.
- `post_id`: chave estrangeira para `posts.id`.
- `autor`: nome do leitor.
- `email`: e-mail do leitor.
- `trecho`: resumo do comentário.
- `texto`: comentário completo.
- `status`: `Aprovado`, `Pendente` ou `Rejeitado`.
- `published_label`: data textual exibida na interface.

## Relacionamentos

- `posts.category_id` referencia `categories.id`.
- `comments.post_id` referencia `posts.id`.
- `comments` usa `ON DELETE CASCADE`: ao excluir uma publicação, seus comentários são removidos.
- `posts` usa `ON DELETE RESTRICT` com `categories`: uma categoria com publicações não pode ser excluída.

## Migração da Sessão para Tabelas

| Estrutura anterior | Tabela atual | Observação |
| --- | --- | --- |
| `settings` | `settings` | Configurações gerais |
| `users` | `users` | Equipe e leitores |
| `categories` | `categories` | Editorias do portal |
| `posts` | `posts` | Publicações |
| `comments` | `comments` | Comentários |
| `featured_slides` | consulta em `posts` | Destaques são posts com `featured = 1` |

O array `portalData` continua existindo internamente para manter compatibilidade com controllers e views, mas agora ele é montado a partir das tabelas MySQL pelo `PortalRepository`.

## Como Criar o Banco

Na raiz do projeto:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p portal_editorial < database/seed.sql
```

Se o usuário do MySQL for outro, substitua `root`.

## Configuração PDO

A conexão usa o arquivo `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_editorial
DB_USERNAME=root
DB_PASSWORD=
```

A classe responsável pela conexão é `app/Core/Database.php`.

Configurações usadas:
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`
- `PDO::ATTR_EMULATE_PREPARES => false`
- charset `utf8mb4`

## Segurança Contra SQL Injection

As operações de gravação usam prepared statements:

```php
$stmt = $this->pdo->prepare(
    'INSERT INTO comments (id, post_id, autor, email, trecho, texto, status, published_label)
     VALUES (:id, :post_id, :autor, :email, :trecho, :texto, :status, :published_label)'
);

$stmt->execute([
    'id' => $commentId,
    'post_id' => $postId,
    'autor' => $autor,
    'email' => $email,
    'trecho' => $trecho,
    'texto' => $texto,
    'status' => $status,
    'published_label' => $data,
]);
```

Assim, valores enviados por formulários não são concatenados diretamente no SQL.

As consultas fixas de leitura, como `SELECT * FROM posts`, não recebem dados do usuário. O ajuste de `AUTO_INCREMENT` também usa uma lista permitida de tabelas para evitar nomes dinâmicos indevidos.

## Comandos de Verificação

Entrar no MySQL:

```bash
mysql -u root -p
```

Selecionar o banco:

```sql
USE portal_editorial;
```

Ver tabelas:

```sql
SHOW TABLES;
```

Conferir quantidade de registros:

```sql
SELECT COUNT(*) FROM categories;
SELECT COUNT(*) FROM posts;
SELECT COUNT(*) FROM comments;
```

Conferir comentários por status:

```sql
SELECT status, COUNT(*) AS total
FROM comments
GROUP BY status;
```

Conferir últimas alterações:

```sql
SELECT id, title, status
FROM posts
ORDER BY id DESC
LIMIT 5;

SELECT id, autor, status, published_label
FROM comments
ORDER BY id DESC
LIMIT 5;
```

## Observações

- O banco usa `utf8mb4` para suportar acentos, cedilha e caracteres especiais.
- Campos booleanos usam `TINYINT(1)`, padrão comum em MySQL/MariaDB.
- Datas textuais usadas na interface foram preservadas em `published_label`.
- Datas internas de controle ficam em `created_at` e `updated_at`.
- Comentários novos entram como `Pendente` e precisam de aprovação no painel administrativo.
