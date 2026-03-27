# Web Servidor — Site Editorial com Painel Gerencial

## Integrantes
- **Lucas Gabriel**
- **Maria Beatriz**
- **Fernanda Pacheco**

## Sobre o projeto
Este projeto está sendo desenvolvido para a disciplina de **Desenvolvimento Web Servidor**.

A proposta é construir um **site editorial tipo blog**, com área pública para leitura de publicações e uma **área administrativa** para gerenciamento do conteúdo, categorias, usuários da equipe e configurações gerais do sistema.

O protótipo visual foi desenvolvido no Figma e está disponível nesse link:

**Protótipo:**  
<https://www.figma.com/design/LlLAwxa2hDMQH0cUZGuxUr/Web-Servidor?node-id=1-5&t=dptCdPAwdHbfjG0m-1>

## Objetivos do sistema
O sistema deverá permitir:

- exibição de publicações no site público;
- organização por categorias;
- autenticação de usuários comentaristas;
- comentários em publicações;
- autenticação da equipe administrativa;
- gerenciamento de categorias, publicações, usuários da equipe e configurações do site.

## Escopo funcional
Com base no escopo do projeto fileciteturn0file0, o sistema está dividido em dois grandes módulos.

### Área pública
- **Home** com destaques configuráveis, últimas publicações e navegação por categorias;
- **Listagem por categoria**;
- **Página de detalhe da publicação** com conteúdo completo;
- **Área de comentários** para usuários autenticados;
- **Cadastro e login** de usuário comentarista.

### Painel gerencial
- **Login da equipe**;
- **CRUD de usuários da equipe**;
- **CRUD de categorias**;
- **CRUD de publicações**;
- **Gestão de comentários**;
- **Configurações gerais do site**.

## Tecnologias previstas
De acordo com o escopo definido, o projeto deverá seguir os seguintes requisitos técnicos fileciteturn0file0:

- **PHP 8+**
- **Arquitetura MVC**
- **MySQL ou MariaDB**
- **HTML/CSS/JavaScript** no frontend


## Estrutura esperada do projeto
A estrutura abaixo é uma sugestão para organização do repositório:

```bash
/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── config/
│   ├── database.php
│   └── app.php
├── public/
│   ├── assets/
│   └── index.php
├── database/
│   └── schema.sql
├── .env.example
└── README.md
```

## Instalação e configuração

### Requisitos
Antes de executar o projeto, é necessário ter instalado:

- **PHP 8.0 ou superior**
- **MySQL ou MariaDB**
- **Apache/Nginx** ou ambiente local como **XAMPP**

### Passos para instalação
1. Clone o repositório:
   ```bash
   git clone <url-do-repositorio>
   ```

2. Acesse a pasta do projeto:
   ```bash
   cd web-servidor
   ```

3. Configure o ambiente:
   - copie o arquivo `.env.example` para `.env`, se existir;
   - ajuste as credenciais do banco de dados;
   - configure a URL base do sistema.

4. Crie o banco de dados no MySQL/MariaDB.

5. Importe o arquivo de estrutura do banco:
   ```bash
   mysql -u root -p nome_do_banco < database/schema.sql
   ```

6. Caso existam dependências com Composer:
   ```bash
   composer install
   ```

7. Inicie o servidor local.

### Arquivos de configuração que devem ser atualizados
Os nomes exatos podem variar conforme a implementação final, mas o README do projeto deve manter atualizados principalmente os seguintes arquivos:

- `config/database.php`
- `config/app.php`
- `.env`

### Exemplo de configurações esperadas
```env
APP_NAME=Web Servidor
APP_URL=http://localhost/web-servidor

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_servidor
DB_USERNAME=root
DB_PASSWORD=
```

## Regras de negócio
As principais regras levantadas no escopo são:

- apenas usuários autenticados podem comentar;
- publicações só devem aparecer no site público quando estiverem com **status publicado** e com data válida;
- o **slug** da publicação deve ser único;
- o **nome da categoria** deve ser único;
- os comentários podem seguir fluxo de publicação imediata ou moderação, conforme a decisão final do grupo.

## Funcionalidades previstas
- cadastro e login de usuário comentarista;
- visualização de posts por categoria;
- leitura completa de publicações;
- sistema de comentários;
- cadastro e edição de categorias;
- cadastro e edição de publicações;
- gerenciamento de usuários da equipe;
- gerenciamento de configurações do blog.

## Documentação das telas
Com base no escopo e nos protótipos enviados, o projeto contempla telas da área pública e do painel administrativo, incluindo:

- home;
- listagem de publicações;
- detalhe de publicação;
- login/cadastro;
- dashboard administrativo;
- gerenciamento de posts;
- gerenciamento de categorias;
- gerenciamento de usuários;
- configurações.

## Relatório breve do desenvolvimento
Este projeto está sendo desenvolvido em grupo e poderá sofrer ajustes ao longo da implementação. Até o momento, o trabalho foi organizado da seguinte forma:

### Particularidades do trabalho
> Esta seção pode e deve ser atualizada ao longo do projeto.

- O sistema ainda está em desenvolvimento.
- Algumas funcionalidades podem estar em fase de prototipação e ainda não completamente implementadas.
- Bugs encontrados, limitações temporárias, funcionalidades pendentes e observações de instalação devem ser registrados aqui.

#### Modelo editável para atualização contínua
```md
### Particularidades do trabalho

- [ ] Bug conhecido:
- [ ] Funcionalidade ainda não implementada:
- [ ] Ajuste necessário na instalação:
- [ ] Observação importante:
```

## Atividades dos integrantes
A tabela abaixo foi organizada para ficar **editável**, permitindo acrescentar novas responsabilidades conforme o projeto evoluir.

| Integrante | Responsabilidades atuais | Novas responsabilidades / observações |
|---|---|---|
| Lucas Gabriel | Escopo, protótipo no Figma e lógica do frontend | |
| Maria Beatriz | Interface no frontend e sistema de login | |
| Fernanda Pacheco | Documentação e desenvolvimento de telas | |

### Modelo editável
Copie e atualize a tabela abaixo sempre que necessário:

```md
| Integrante | Responsabilidades atuais | Novas responsabilidades / observações |
|---|---|---|
| Lucas Gabriel | Escopo, protótipo no Figma e lógica do frontend | |
| Maria Beatriz | Interface no frontend e sistema de login | |
| Fernanda Pacheco | Documentação e desenvolvimento de telas | |
```

## Critérios de aceite
Alguns critérios mínimos de aceite definidos pelo escopo são:

- a publicação não deve aparecer publicamente antes de ser publicada;
- o comentário deve exigir autenticação do usuário;
- categorias e posts devem possuir cadastro e edição funcionais;
- as configurações do site devem refletir no frontend;
- o painel deve ser acessível apenas por usuários autorizados da equipe.

## Pendências para atualizar no README futuramente
Durante o andamento do projeto, é importante revisar este README para incluir:

- nome final do repositório;
- instruções reais de execução;
- credenciais iniciais de teste, se houver;
- link de deploy, se houver;
- bibliotecas realmente utilizadas;
- bugs conhecidos e soluções temporárias;
- prints finais ou gifs demonstrando o sistema em funcionamento.

## Licença
```text
Uso acadêmico — projeto desenvolvido para a disciplina de Desenvolvimento Web Servidor.
```
