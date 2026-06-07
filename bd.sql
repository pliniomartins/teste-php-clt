CREATE DATABASE IF NOT EXISTS crud_contatos;
USE crud_contatos;

-- Tabela original de contatos (mantida, apenas melhorada com soft delete)
CREATE TABLE IF NOT EXISTS contatos (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(80)  NOT NULL,
    telefone  VARCHAR(20)  DEFAULT NULL,
    email     VARCHAR(80)  DEFAULT NULL,
    ativo     TINYINT(1)   NOT NULL DEFAULT 1,
    deleted_at TIMESTAMP   NULL DEFAULT NULL
);

-- Nova tabela de usuários (requisito do exercício)
CREATE TABLE IF NOT EXISTS usuarios (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    senha         VARCHAR(255) NOT NULL,
    ativo         TINYINT(1)   NOT NULL DEFAULT 1,
    deleted_at    TIMESTAMP    NULL DEFAULT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
