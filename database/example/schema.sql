-- Base de datos de ejemplo para la aplicación
CREATE DATABASE IF NOT EXISTS issueflow_sql_lab
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE issueflow_sql_lab;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT uq_users_email UNIQUE (email)
);

CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT uq_categories_name UNIQUE (name)
);

CREATE TABLE IF NOT EXISTS tickets (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code CHAR(8) NOT NULL,
    title VARCHAR(180) NOT NULL,
    priority VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'open',
    user_id BIGINT UNSIGNED,
    category_id BIGINT UNSIGNED,
    create_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT uq_tickets_code UNIQUE (code),
    CONSTRAINT chk_tickets_priority CHECK (priority IN ('urgent', 'high', 'normal', 'low')),
    CONSTRAINT chk_tickets_status CHECK (status IN ('open', 'in_progress', 'closed')),
    CONSTRAINT fk_tickets_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT fk_tickets_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
);
