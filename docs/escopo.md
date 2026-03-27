# Escopo simples — site editorial com painel gerencial *(tipo blog)*

## Visão

Site público para **leitura de publicações** organizadas por **categorias**, com **comentários** por publicação (usuários cadastrados). **Painel administrativo** para a equipe editorial gerenciar conteúdo, usuários da equipe e **configurações globais** do que aparece no site.

---

## Personas

| Persona | Descrição |
|--------|-----------|
| **Visitante** | Navega categorias e publicações; para comentar, cria conta ou faz login. |
| **Usuário comentarista** | Cadastro no site; comenta em publicações *(moderação opcional no escopo)*. |
| **Editor / Admin (painel)** | Gerencia usuários da equipe, categorias, publicações, comentários *(se houver moderação)* e configurações do blog. |

---

## Módulos e telas (público)

- **Home** — Destaques configuráveis (ordem, título, blocos definidos no painel), últimas publicações, links para categorias.
- **Lista por categoria** — Tabela / listagem de publicações da categoria *(conta como lista/tabela)*.
- **Detalhe da publicação** — Texto completo + área de comentários (lista + formulário de novo comentário para usuário logado).
- **Cadastro de usuário comentarista** + **Login** — *(Login não conta como “formulário obrigatório” do enunciado; ainda são necessários ≥3 outros formulários.)*

---

## Painel gerencial (área autenticada — equipe)

1. **Login da equipe** (sessão).
2. **CRUD de usuários da equipe** — Papéis (ex.: admin, editor); formulário de cadastro/edição.
3. **CRUD de categorias** — Formulário de cadastro/edição + listagem.
4. **CRUD de publicações (posts)** — Formulário (título, slug, resumo, corpo, categoria, data, status rascunho/publicado) + listagem.
5. **Gestão de comentários** *(se houver moderação)* — Listagem + ações (aprovar/excluir) ou apenas exclusão.
6. **Configurações do site** — Formulário único ou seção com campos como: nome do site, slogan, textos de rodapé, quantidade de itens na home, opções de exibir/ocultar blocos, e-mail de contato exibido etc. *(tudo persistido e refletido no front-end)*.

---

## Formulários distintos *(≥3, sem contar login)*

Sugestão mínima:

1. Cadastro / edição de **categoria**
2. Cadastro / edição de **publicação**
3. Cadastro de **usuário comentarista** e/ou **comentário**
4. **Configurações gerais**

Com isso já se ultrapassa o mínimo de três formulários.

---

## Regras de negócio *(simples)*

- Só usuários logados comentam *(ou permitir visitante apenas leitura — definir e documentar)*.
- Publicação só aparece no público se **status = publicado** e **data válida**.
- Comentários: **imediato** ou **pendente de moderação** — escolher uma opção e documentar.
- **Slug** da publicação único; **nome** da categoria único.

---

## Requisitos técnicos *(alinhados ao trabalho)*

- **PHP 8+**, padrão **MVC** (Controllers + Models + views só com HTML/PHP “limpo”).
- **MySQL / MariaDB**: usuários, sessões, categorias, publicações, comentários, configurações.
- **Validação e erros apenas no servidor (PHP)**; interface com mensagens claras.
- **Sem Laravel** nem outro framework PHP; **Composer** apenas para bibliotecas pontuais, se necessário.
- **Documento de instalação**: Docker ou XAMPP, variáveis de ambiente, `schema.sql`, usuário admin inicial.

---

## Entregáveis do escopo

- Lista de **telas** e **fluxos** (cerca de um parágrafo cada).
- **Modelo de dados** (tabelas e campos principais).
- **Critérios de aceite** (ex.: *“publicação invisível até publicar”*, *“comentário só se logado”*).
