<?php

/**
 * Model Endereco
 * Representa os endereços vinculados a um usuário.
 * Um usuário pode ter vários endereços (1 para N).
 * O CEP é buscado automaticamente via API ViaCEP (JavaScript).
 */
class Endereco
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
     * Salva um novo endereço vinculado a um usuário
     */
    public function save()
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "INSERT INTO enderecos (usuario_id, cep, logradouro, numero, complemento, bairro, cidade, estado)
                 VALUES (:usuario_id, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado)"
            );
            $stmt->execute([
                ':usuario_id'  => $this->usuario_id,
                ':cep'         => $this->cep,
                ':logradouro'  => $this->logradouro,
                ':numero'      => $this->numero,
                ':complemento' => $this->complemento,
                ':bairro'      => $this->bairro,
                ':cidade'      => $this->cidade,
                ':estado'      => $this->estado,
            ]);
            $this->id = $conexao->lastInsertId();
            return $stmt->rowCount();

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao salvar endereço: " . $e->getMessage());
        }
    }

    /**
     * Retorna todos os endereços ativos de um usuário
     * @param int $usuario_id
     */
    public static function findByUsuario(int $usuario_id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "SELECT * FROM enderecos
                  WHERE usuario_id = :usuario_id
                    AND deleted_at IS NULL
                  ORDER BY criado_em ASC"
            );
            $stmt->execute([':usuario_id' => $usuario_id]);
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, Endereco::class);
            return !empty($result) ? $result : false;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao buscar endereços: " . $e->getMessage());
        }
    }

    /**
     * Exclusão lógica de um endereço
     */
    public static function destroy(int $id)
    {
        try {
            $conexao = Conexao::getInstance();
            $stmt    = $conexao->prepare(
                "UPDATE enderecos SET deleted_at = NOW(), ativo = 0
                  WHERE id = :id AND deleted_at IS NULL"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;

        } catch (\PDOException $e) {
            throw new RuntimeException("Erro ao excluir endereço: " . $e->getMessage());
        }
    }
}
