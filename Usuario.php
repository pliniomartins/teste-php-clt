<?php

class Usuario
{
    private $atributos;

    public function __construct() {}

    public function __set(string $atributo, $valor)
    {
        $this->atributos[$atributo] = $valor;
        return $this;
    }

    public function __get(string $atributo)
    {
        return $this->atributos[$atributo] ?? null;
    }

    public function __isset($atributo)
    {
        return isset($this->atributos[$atributo]);
    }

    /**
     * Salva ou atualiza o usuário
     * Senha sempre armazenada como hash bcrypt
     */
    public function save()
    {
        try {
            $conexao = Conexao::getInstance();

            if (!isset($this->id)) {
                if (self::findByEmail($this->email)) {
                    throw new RuntimeException("O e-mail '{$this->email}' já está cadastrado.");
                }

                $stmt = $conexao->prepare(
                    "INSERT INTO usuarios (nome, email, senha, ativo)
                     VALUES (:nome, :email, :senha, :ativo)"
                );
                $stmt->execute([
                    ':nome'  => $this->nome,
                    ':email' => $this->email,
                    ':senha' => password_hash($this->senha, PASSWORD_BCRYPT),
                    ':ativo' => isset($this->ativo) ? (int) $this->ativo : 1,
                ]);
                $this->id = $conexao->lastInsertId();
            } else {
                $stmt = $conexao->prepare(
                    "UPDATE usuarios SET nome = :nome, email = :email, ativo = :ativo
                      WHERE id = :id AND deleted_at IS NULL"
                );
                $stmt->execute([
                    ':nome'  => $this->nome,
                    ':email' => $this->email,
                    ':ativo' => (int) $this->ativo,
                    ':id'    => $this->id,
                ]);
            }

            return $stmt->rowCount();

        } catch (RuntimeException $e) {
            throw $e;
        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao salvar usuário: " . $e->getMessage());
        }
    }

    /**
     * Lista usuários ativos com busca opcional por nome
     */
    public static function all(?string $busca = null)
    {
        try {
            $conexao = Conexao::getInstance();

            if ($busca) {
                $stmt = $conexao->prepare(
                    "SELECT id, nome, email, ativo, criado_em, atualizado_em
                       FROM usuarios
                      WHERE deleted_at IS NULL AND nome LIKE :busca
                      ORDER BY nome ASC"
                );
                $stmt->execute([':busca' => "%$busca%"]);
            } else {
                $stmt = $conexao->prepare(
                    "SELECT id, nome, email, ativo, criado_em, atualizado_em
                       FROM usuarios WHERE deleted_at IS NULL ORDER BY nome ASC"
                );
                $stmt->execute();
            }

            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, Usuario::class);
            return !empty($result) ? $result : false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao listar usuários: " . $e->getMessage());
        }
    }

    /**
     * Encontra usuário por ID
     */
    public static function find($id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "SELECT id, nome, email, ativo, criado_em, atualizado_em
                   FROM usuarios WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => (int) $id]);
            $resultado = $stmt->fetchObject(Usuario::class);
            return $resultado ?: false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao buscar usuário: " . $e->getMessage());
        }
    }

    /**
     * Encontra usuário por e-mail (validação de duplicidade)
     */
    public static function findByEmail(string $email)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "SELECT id, nome, email FROM usuarios
                  WHERE email = :email AND deleted_at IS NULL"
            );
            $stmt->execute([':email' => $email]);
            $resultado = $stmt->fetchObject(Usuario::class);
            return $resultado ?: false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao buscar usuário por e-mail: " . $e->getMessage());
        }
    }

    /**
     * Exclusão lógica
     */
    public static function destroy($id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "UPDATE usuarios SET deleted_at = NOW(), ativo = 0
                  WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => (int) $id]);
            return $stmt->rowCount() > 0;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao excluir usuário: " . $e->getMessage());
        }
    }
}
