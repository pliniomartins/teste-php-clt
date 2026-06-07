<?php

/**
 * Model Telefone
 * Representa os telefones vinculados a um contato.
 * Um contato pode ter vários telefones (1 para N).
 */
class Telefone
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
     * Salva um novo telefone vinculado a um contato
     */
    public function save()
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "INSERT INTO telefones (contato_id, telefone)
                 VALUES (:contato_id, :telefone)"
            );
            $stmt->execute([
                ':contato_id' => $this->contato_id,
                ':telefone'   => $this->telefone,
            ]);
            $this->id = $conexao->lastInsertId();
            return $stmt->rowCount();

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao salvar telefone: " . $e->getMessage());
        }
    }

    /**
     * Retorna todos os telefones ativos de um contato
     * @param int $contato_id
     * @return array|bool
     */
    public static function findByContato(int $contato_id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "SELECT * FROM telefones
                  WHERE contato_id = :contato_id
                    AND deleted_at IS NULL
                  ORDER BY criado_em ASC"
            );
            $stmt->execute([':contato_id' => $contato_id]);
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, Telefone::class);
            return !empty($result) ? $result : false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao buscar telefones: " . $e->getMessage());
        }
    }

    /**
     * Exclusão lógica de um telefone
     * @param int $id
     */
    public static function destroy(int $id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "UPDATE telefones SET deleted_at = NOW(), ativo = 0
                  WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao excluir telefone: " . $e->getMessage());
        }
    }

    /**
     * Exclui logicamente todos os telefones de um contato
     * @param int $contato_id
     */
    public static function destroyByContato(int $contato_id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "UPDATE telefones SET deleted_at = NOW(), ativo = 0
                  WHERE contato_id = :contato_id AND deleted_at IS NULL"
            );
            $stmt->execute([':contato_id' => $contato_id]);

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao excluir telefones do contato: " . $e->getMessage());
        }
    }
}
