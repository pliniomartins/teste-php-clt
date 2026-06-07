<?php

class UsuariosController extends Controller
{
    /**
     * Lista todos os usuários com suporte a busca
     */
    public function listar()
    {
        try {
            $busca    = $this->request->busca ?: null;
            $usuarios = Usuario::all($busca);
            $flash    = Controller::getFlash();
            return $this->view('usuarios_grade', [
                'usuarios' => $usuarios,
                'flash'    => $flash,
                'busca'    => $busca,
            ]);
        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    /**
     * Formulário para criar novo usuário
     */
    public function criar()
    {
        return $this->view('usuarios_form');
    }

    /**
     * Formulário para editar usuário com seus endereços
     */
    public function editar($dados)
    {
        try {
            $id      = (int) $dados['id'];
            $usuario = Usuario::find($id);

            if (!$usuario) {
                throw new RuntimeException("Usuário #$id não encontrado.");
            }

            $enderecos = Endereco::findByUsuario($id);
            return $this->view('usuarios_form', [
                'usuario'   => $usuario,
                'enderecos' => $enderecos,
            ]);

        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    /**
     * Salva novo usuário com seus endereços
     */
    public function salvar()
    {
        try {
            $this->validar(['nome', 'email', 'senha', 'confirmar_senha']);

            $usuario        = new Usuario;
            $usuario->nome  = $this->request->nome;
            $usuario->email = $this->request->email;
            $usuario->senha = $this->request->senha;
            $usuario->ativo = 1;
            $usuario->save();

            // Salva os endereços enviados
            $this->salvarEnderecos((int) $usuario->id);

            $this->flash('sucesso', 'Usuário cadastrado com sucesso!');
            $this->redirecionar('?controller=UsuariosController&method=listar');

        } catch (RuntimeException $e) {
            return $this->view('usuarios_form', ['erro' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza usuário e seus endereços
     */
    public function atualizar($dados)
    {
        try {
            $id      = (int) $dados['id'];
            $usuario = Usuario::find($id);

            if (!$usuario) {
                throw new RuntimeException("Usuário #$id não encontrado.");
            }

            $this->validar(['nome', 'email']);

            $usuario->nome  = $this->request->nome;
            $usuario->email = $this->request->email;
            $usuario->ativo = $this->request->ativo ?? $usuario->ativo;
            $usuario->save();

            $this->flash('sucesso', 'Usuário atualizado com sucesso!');
            $this->redirecionar('?controller=UsuariosController&method=listar');

        } catch (RuntimeException $e) {
            $usuario = Usuario::find((int) $dados['id']);
            return $this->view('usuarios_form', ['usuario' => $usuario, 'erro' => $e->getMessage()]);
        }
    }

    /**
     * Exclusão lógica do usuário
     */
    public function excluir($dados)
    {
        try {
            $id = (int) $dados['id'];

            if (!Usuario::destroy($id)) {
                throw new RuntimeException("Não foi possível excluir o usuário #$id.");
            }

            $this->flash('sucesso', 'Usuário excluído com sucesso!');
            $this->redirecionar('?controller=UsuariosController&method=listar');

        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    /**
     * Exclui um endereço específico do usuário
     */
    public function excluirEndereco($dados)
    {
        try {
            $id         = (int) $dados['id'];
            $usuario_id = (int) $dados['usuario_id'];
            Endereco::destroy($id);

            $this->flash('sucesso', 'Endereço removido com sucesso!');
            $this->redirecionar("?controller=UsuariosController&method=editar&id=$usuario_id");

        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    // -------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------

    /**
     * Salva os endereços do formulário vinculados ao usuário
     */
    private function salvarEnderecos(int $usuario_id): void
    {
        $enderecos = $_POST['enderecos'] ?? [];
        foreach ($enderecos as $dados) {
            if (!empty($dados['cep']) && !empty($dados['logradouro'])) {
                $end               = new Endereco;
                $end->usuario_id   = $usuario_id;
                $end->cep          = $dados['cep'];
                $end->logradouro   = $dados['logradouro'];
                $end->numero       = $dados['numero'] ?? '';
                $end->complemento  = $dados['complemento'] ?? '';
                $end->bairro       = $dados['bairro'];
                $end->cidade       = $dados['cidade'];
                $end->estado       = $dados['estado'];
                $end->save();
            }
        }
    }

    private function validar(array $campos): void
    {
        foreach ($campos as $campo) {
            if (!$this->request->$campo) {
                throw new RuntimeException("O campo '$campo' é obrigatório.");
            }
        }

        if ($this->request->email && !filter_var($this->request->email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("O e-mail informado é inválido.");
        }

        if ($this->request->senha && strlen($this->request->senha) < 6) {
            throw new RuntimeException("A senha deve ter no mínimo 6 caracteres.");
        }

        if ($this->request->senha && $this->request->confirmar_senha) {
            if ($this->request->senha !== $this->request->confirmar_senha) {
                throw new RuntimeException("As senhas não conferem.");
            }
        }
    }

    private function exibirErro(string $mensagem): void
    {
        echo '<div class="container mt-3">';
        echo '<div class="alert alert-danger">' . htmlspecialchars($mensagem) . '</div>';
        echo '<a href="?controller=UsuariosController&method=listar" class="btn btn-secondary">Voltar</a>';
        echo '</div>';
    }
}
