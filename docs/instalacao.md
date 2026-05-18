# Guia de Instalação

Este guia explica como configurar e executar o Portal Editorial localmente com PHP 8+, MySQL e PDO.

## Requisitos

- PHP 8.0 ou superior.
- Composer
- Extensão `pdo_mysql` ativa.
- MySQL 8.0 ou MariaDB compatível.
- Navegador web.
- Terminal.

## 1. Acessar a Pasta do Projeto

```bash
cd /home/fernanda/Documentos/GitHub/web-servidor
```

Se o projeto estiver em outra pasta, ajuste o caminho.

## 2. Instalar dependências (Composer)

Na raiz do projeto:

```bash
composer install
```

Isso cria a pasta `vendor/` com o autoload PSR-4 (`App\`) e os pacotes:
- `vlucas/phpdotenv` — leitura do `.env`
- `bramus/router` — rotas HTTP por path da URI

Sem o `vendor/`, a aplicação não inicia.

## 3. Iniciar o MySQL

Em Linux/Ubuntu:

```bash
sudo service mysql start
```

Ou:

```bash
sudo systemctl start mysql
```

Teste o acesso:

```bash
mysql -u root -p
```

Para sair do MySQL:

```sql
exit;
```

## 4. Verificar o Driver PDO do MySQL

```bash
php -m | grep pdo_mysql
```

Se aparecer `pdo_mysql`, o PHP está pronto para conectar ao MySQL.

Se não aparecer, instale:

```bash
sudo apt install php-mysql
```

Depois confira novamente:

```bash
php -m | grep pdo_mysql
```

## 5. Criar o Banco de Dados

Na raiz do projeto, execute:

```bash
mysql -u root -p < database/schema.sql
```

Esse comando cria o banco `portal_editorial` e as tabelas.

## 6. Inserir os Dados Iniciais

```bash
mysql -u root -p portal_editorial < database/seed.sql
```

Esse comando popula o banco com categorias, publicações, comentários, usuários e configurações iniciais.

## 7. Configurar o `.env`

Crie o arquivo `.env` a partir do exemplo:

```bash
cp .env.example .env
```

Abra o arquivo `.env` e ajuste conforme seu MySQL:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_editorial
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Se o usuário `root` não tiver senha, deixe:

```env
DB_PASSWORD=
```

### macOS (Laravel Herd)

Com o site apontando para a pasta `public/` do projeto, o Herd usa PHP e rewrite automaticamente. Use `DB_HOST=127.0.0.1` no `.env` e o MySQL local (ou serviço do Herd, se configurado).

### Docker

Se o MySQL rodar em um container na rede Docker, ajuste o host:

```env
DB_HOST=mariadb
```

O nome do serviço deve coincidir com o `docker-compose.yml` do ambiente.

## 8. Rodar a Aplicação

Na raiz do projeto:

```bash
php -S localhost:8000 -t public
```

Abra no navegador:

```text
http://localhost:8000
```

## 8. Credenciais de Teste

Administrador:

```text
E-mail: admin@admin.com
Senha: 123456
```

Usuário público:

```text
E-mail: leitor@oeditorial.com.br
Senha: 123456
```

Também é possível criar uma conta pública pela tela de login/cadastro.

## 9. Testar Persistência no Banco

Com a aplicação aberta:

1. Entre no portal administrativo.
2. Crie ou edite uma categoria, publicação ou comentário.
3. Consulte o banco:

```bash
mysql -u root -p
```

```sql
USE portal_editorial;

SELECT id, title, status
FROM posts
ORDER BY id DESC
LIMIT 5;

SELECT id, autor, status
FROM comments
ORDER BY id DESC
LIMIT 5;
```

Se as alterações aparecerem nas tabelas, a persistência via PDO está funcionando.

## Problemas Comuns

### `could not find driver`

O PHP não está com `pdo_mysql` ativo.

Solução:

```bash
sudo apt install php-mysql
```

### `Can't connect to local MySQL server`

O MySQL pode não estar iniciado.

Solução:

```bash
sudo service mysql start
```

### `Access denied for user`

Usuário ou senha do `.env` estão incorretos.

Solução:
- confira `DB_USERNAME`;
- confira `DB_PASSWORD`;
- teste o login com `mysql -u usuario -p`.

### Banco não encontrado

O `schema.sql` ainda não foi executado.

Solução:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p portal_editorial < database/seed.sql
```

## Arquivos de Configuração

- `.env`: credenciais reais da máquina local. Não deve ser versionado.
- `.env.example`: modelo seguro para documentação.
- `composer.json` / `composer.lock`: dependências e autoload PSR-4.
- `database/schema.sql`: criação do banco.
- `database/seed.sql`: carga inicial.
- `app/Core/Database.php`: conexão PDO.
