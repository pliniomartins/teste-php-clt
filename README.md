# 📒 Agenda de Contatos — Teste Prático PHP

Teste prático desenvolvido para a vaga de estágio em desenvolvimento PHP na **CMTECH/MEXX**.

---

## Sobre o Projeto

Sistema web de gerenciamento de contatos e usuários desenvolvido em PHP puro com arquitetura MVC. O projeto foi construído a partir de um CRUD de contatos já existente, que foi melhorado e expandido com novas funcionalidades.

---

## Funcionalidades

**Contatos**
- Listar, cadastrar, editar e excluir contatos
- Busca por nome
- Exclusão lógica — o registro não é apagado do banco

**Usuários**
- Listar, cadastrar, editar e excluir usuários
- Busca por nome
- Senha armazenada com hash bcrypt
- Validação de e-mail duplicado
- Confirmação de senha no cadastro

**Geral**
- Mensagem de confirmação após cada ação
- Proteção contra SQL Injection
- Proteção contra XSS
- Tratamento de exceções em todas as camadas

---

## Padrões de Projeto Utilizados

- **Singleton** — uma única conexão PDO por requisição (`Conexao.php`)
- **Active Record** — objetos que se persistem no banco (`Contato.php`, `Usuario.php`)
- **MVC** — separação clara entre Model, View e Controller
- **Front Controller** — ponto de entrada único da aplicação (`index.php`)
- **Template Method** — método `view()` herdado por todos os controllers
- **Flash Message** — notificações via sessão PHP

---

## Melhorias no CRUD Original

- Substituído SQL concatenado por prepared statements PDO
- Corrigido `count()` que usava `exec()` incorretamente
- Adicionado `htmlspecialchars()` em todas as views
- Adicionado try/catch em todas as camadas
- Adicionado feedback visual ao usuário após cada ação
- Substituída exclusão física por exclusão lógica com `deleted_at`

---

## Como Rodar o Projeto

**Requisitos:** PHP 7.4+, MySQL 5.7+, Laragon ou XAMPP

**1. Clone o repositório:**
```bash
git clone https://github.com/pliniomartins/teste-php-estagio.git
```

**2. Coloque a pasta no diretório do servidor:**
```
C:\laragon\www\agenda-contatos\
```

**3. Crie o banco de dados:**

Acesse o phpMyAdmin, crie um banco chamado `testephp` e execute o arquivo `bd.sql`.

**4. Configure a conexão em `Conexao.php`:**
```php
new \PDO('mysql:host=localhost;port=3306;dbname=testephp', 'root', 'sua_senha');
```

**5. Acesse no navegador:**
```
http://agenda-contatos.test/
```

---

## Tecnologias Utilizadas

- PHP, MySQL, HTML5, CSS3, Bootstrap 4, JavaScript, jQuery e Font Awesome

---

## Autor

**Plinio Martins**  
Candidato à vaga de Estágio em Desenvolvimento PHP — CMTECH / MEXX
