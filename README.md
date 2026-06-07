# 📒 Agenda de Contatos — Teste Prático PHP (CLT)

Teste prático desenvolvido para a vaga CLT em desenvolvimento PHP na **CMTECH/MEXX**.

---

## Sobre o Projeto

Sistema web de gerenciamento de contatos e usuários desenvolvido em PHP puro com arquitetura MVC. O projeto foi construído a partir de um CRUD de contatos já existente, que foi melhorado e expandido com novas funcionalidades.

---

## Funcionalidades

**Contatos**
- Listar, cadastrar, editar e excluir contatos
- Múltiplos telefones por contato — adiciona e remove dinamicamente
- Busca por nome
- Exclusão lógica — o registro não é apagado do banco

**Usuários**
- Listar, cadastrar, editar e excluir usuários
- Múltiplos endereços por usuário
- Busca de CEP automática via API ViaCEP — preenche logradouro, bairro, cidade e estado automaticamente
- Busca por nome
- Senha armazenada com hash bcrypt
- Validação de e-mail duplicado
- Confirmação de senha no cadastro
- Exclusão lógica

**Geral**
- Mensagem de confirmação após cada ação (flash message)
- Proteção contra SQL Injection com prepared statements PDO
- Proteção contra XSS com htmlspecialchars em todas as views
- Tratamento de exceções em todas as camadas

---

## Padrões de Projeto Utilizados

- **Singleton** — uma única conexão PDO por requisição (`Conexao.php`)
- **Active Record** — objetos que se persistem no banco (`Contato.php`, `Usuario.php`, `Telefone.php`, `Endereco.php`)
- **MVC** — separação clara entre Model, View e Controller
- **Front Controller** — ponto de entrada único da aplicação (`index.php`)
- **Template Method** — método `view()` herdado por todos os controllers
- **Flash Message** — notificações via sessão PHP após cada ação

---

## Melhorias no CRUD Original

- Substituído SQL concatenado por prepared statements PDO
- Corrigido `count()` que usava `exec()` incorretamente
- Adicionado `htmlspecialchars()` em todas as views
- Adicionado try/catch em todas as camadas
- Adicionado feedback visual ao usuário após cada ação
- Substituída exclusão física por exclusão lógica com `deleted_at`
- Adicionado suporte a múltiplos telefones por contato

---

## Bônus Implementados

- Múltiplos endereços por usuário
- Busca automática de CEP via API ViaCEP usando JavaScript fetch
- Validação de e-mail duplicado no cadastro de usuários

---

## Como Rodar o Projeto

**Requisitos:** PHP 7.4+, MySQL 5.7+, Laragon ou XAMPP

**1. Clone o repositório:**
```bash
git clone https://github.com/pliniomartins/teste-php-clt.git
```

**2. Coloque a pasta no diretório do servidor:**
```
C:\laragon\www\teste-php-clt\
```

**3. Crie o banco de dados:**

Acesse o phpMyAdmin em `http://localhost/phpmyadmin`, crie um banco chamado `testephp` e execute o arquivo `bd.sql`.

**4. Configure a conexão em `Conexao.php`:**
```php
new \PDO('mysql:host=localhost;port=3306;dbname=testephp', 'root', 'sua_senha');
```

**5. Acesse no navegador:**
```
http://localhost/teste-php-clt/
```

---

## Estrutura de Arquivos

```
teste-php-clt/
├── index.php                  — Front Controller
├── Conexao.php                — Conexão PDO (Singleton)
├── Controller.php             — Controller base
├── Request.php                — Leitura de dados da requisição
├── Contato.php                — Model de Contatos
├── ContatosController.php     — Controller de Contatos
├── Telefone.php               — Model de Telefones
├── Usuario.php                — Model de Usuários
├── UsuariosController.php     — Controller de Usuários
├── Endereco.php               — Model de Endereços
├── form.php                   — View formulário de Contatos
├── grade.php                  — View listagem de Contatos
├── usuarios_form.php          — View formulário de Usuários
├── usuarios_grade.php         — View listagem de Usuários
├── bd.sql                     — Script SQL do banco de dados
└── respostas.txt              — Respostas às questões técnicas
```

---

## Banco de Dados

```sql
-- Contatos
CREATE TABLE contatos (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(80)  NOT NULL,
    email      VARCHAR(80)  DEFAULT NULL,
    ativo      TINYINT(1)   NOT NULL DEFAULT 1,
    deleted_at TIMESTAMP    NULL DEFAULT NULL
);

-- Telefones (vários por contato)
CREATE TABLE telefones (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contato_id    INT UNSIGNED NOT NULL,
    telefone      VARCHAR(20)  NOT NULL,
    ativo         TINYINT(1)   NOT NULL DEFAULT 1,
    deleted_at    TIMESTAMP    NULL DEFAULT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contato_id) REFERENCES contatos(id)
);

-- Usuários
CREATE TABLE usuarios (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE,
    senha         VARCHAR(255) NOT NULL,
    ativo         TINYINT(1)   NOT NULL DEFAULT 1,
    deleted_at    TIMESTAMP    NULL DEFAULT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Endereços (vários por usuário)
CREATE TABLE enderecos (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id    INT UNSIGNED NOT NULL,
    cep           VARCHAR(9)   NOT NULL,
    logradouro    VARCHAR(150) NOT NULL,
    numero        VARCHAR(10)  NOT NULL,
    complemento   VARCHAR(100) DEFAULT NULL,
    bairro        VARCHAR(100) NOT NULL,
    cidade        VARCHAR(100) NOT NULL,
    estado        CHAR(2)      NOT NULL,
    ativo         TINYINT(1)   NOT NULL DEFAULT 1,
    deleted_at    TIMESTAMP    NULL DEFAULT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

---

## Tecnologias Utilizadas

- PHP, MySQL, HTML5, CSS3, Bootstrap 4, JavaScript (fetch API), jQuery e Font Awesome

---

## Observações

As questões de lógica e banco de dados não conseguir fazer, pois mão mostra nenhuma imagem ouo algo que eu possa realmente responder.

---

## Autor

**Plinio Martins**
Candidato à vaga CLT em Desenvolvimento PHP — CMTECH / MEXX
