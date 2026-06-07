<?php

class Contato
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
     * Salva ou atualiza o contato usando prepared statements
     */
    public function save()
    {
        try {
            $conexao = Conexao::getInstance();

            if (!isset($this->id)) {
                $stmt = $conexao->prepare(
                    "INSERT INTO contatos (nome, telefone, email)
                     VALUES (:nome, :telefone, :email)"
                );
                $stmt->execute([
                    ':nome'     => $this->nome,
                    ':telefone' => $this->telefone,
                    ':email'    => $this->email,
                ]);
                $this->id = $conexao->lastInsertId();
            } else {
                $stmt = $conexao->prepare(
                    "UPDATE contatos SET nome = :nome, telefone = :telefone, email = :email
                     WHERE id = :id AND deleted_at IS NULL"
                );
                $stmt->execute([
                    ':nome'     => $this->nome,
                    ':telefone' => $this->telefone,
                    ':email'    => $this->email,
                    ':id'       => $this->id,
                ]);
            }

            return $stmt->rowCount();

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao salvar contato: " . $e->getMessage());
        }
    }

    /**
     * Lista contatos ativos com busca opcional por nome
     * @param string|null $busca
     */
    public static function all(?string $busca = null)
    {
        try {
            $conexao = Conexao::getInstance();

            if ($busca) {
                $stmt = $conexao->prepare(
                    "SELECT * FROM contatos
                      WHERE deleted_at IS NULL
                        AND nome LIKE :busca
                      ORDER BY nome ASC"
                );
                $stmt->execute([':busca' => "%$busca%"]);
            } else {
                $stmt = $conexao->prepare(
                    "SELECT * FROM contatos WHERE deleted_at IS NULL ORDER BY nome ASC"
                );
                $stmt->execute();
            }

            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, Contato::class);
            return !empty($result) ? $result : false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao listar contatos: " . $e->getMessage());
        }
    }

    /**
     * Encontra contato por ID
     */
    public static function find($id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "SELECT * FROM contatos WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => (int) $id]);
            $resultado = $stmt->fetchObject(Contato::class);
            return $resultado ?: false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao buscar contato: " . $e->getMessage());
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
                "UPDATE contatos SET deleted_at = NOW(), ativo = 0
                  WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => (int) $id]);
            return $stmt->rowCount() > 0;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao excluir contato: " . $e->getMessage());
        }
    }

    /**
     * Contagem de contatos ativos
     */
    public static function count()
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->query(
                "SELECT COUNT(*) FROM contatos WHERE deleted_at IS NULL"
            );
            return (int) $stmt->fetchColumn();

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao contar contatos: " . $e->getMessage());
        }
    }
}
