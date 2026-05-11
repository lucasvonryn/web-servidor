-- Banco de dados do Portal Editorial
-- MySQL/MariaDB

CREATE DATABASE IF NOT EXISTS portal_editorial
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE portal_editorial;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS settings;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE settings (
    id TINYINT UNSIGNED NOT NULL PRIMARY KEY DEFAULT 1,
    nome_site VARCHAR(120) NOT NULL,
    slogan VARCHAR(255) NULL,
    about_text TEXT NULL,
    itens_home TINYINT UNSIGNED NOT NULL DEFAULT 6,
    show_featured TINYINT(1) NOT NULL DEFAULT 1,
    show_latest TINYINT(1) NOT NULL DEFAULT 1,
    exibir_comentarios TINYINT(1) NOT NULL DEFAULT 1,
    contact_email VARCHAR(180) NULL,
    footer_links VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_settings_single_row CHECK (id = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    senha_hash VARCHAR(255) NULL,
    papel ENUM('Administrador', 'Editora', 'Editor', 'Leitor') NOT NULL DEFAULT 'Editor',
    status ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    created_at DATE NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL,
    name VARCHAR(120) NOT NULL,
    tag_class VARCHAR(120) NOT NULL,
    accent VARCHAR(80) NOT NULL,
    cover VARCHAR(255) NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE posts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    slug VARCHAR(160) NOT NULL,
    title VARCHAR(220) NOT NULL,
    excerpt TEXT NULL,
    content TEXT NOT NULL,
    author VARCHAR(120) NOT NULL,
    author_short VARCHAR(80) NULL,
    published_label VARCHAR(80) NULL,
    status ENUM('Publicado', 'Rascunho') NOT NULL DEFAULT 'Rascunho',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    cover VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_posts_slug (slug),
    KEY idx_posts_category_id (category_id),
    KEY idx_posts_status_featured (status, featured),
    CONSTRAINT fk_posts_category
        FOREIGN KEY (category_id)
        REFERENCES categories (id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    post_id INT UNSIGNED NOT NULL,
    autor VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    trecho VARCHAR(255) NULL,
    texto TEXT NOT NULL,
    status ENUM('Aprovado', 'Pendente', 'Rejeitado') NOT NULL DEFAULT 'Pendente',
    published_label VARCHAR(80) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_comments_post_id (post_id),
    KEY idx_comments_status (status),
    CONSTRAINT fk_comments_post
        FOREIGN KEY (post_id)
        REFERENCES posts (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
