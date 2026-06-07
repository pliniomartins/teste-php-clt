<?php

class ContatosController extends Controller
{
    /**
     * Lista os contatos ativos com suporte a busca por nome
     */
    public function listar()
    {
        try {
            $busca    = $this->request->busca ?: null;
            $contatos = Contato::all($busca);
            $flash    = Controller::getFlash();
            return $this->view('grade', [
                'contatos' => $contatos,
                'flash'    => $flash,
                'busca'    => $busca,
            ]);
        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    /**
     * Formulário para criar novo contato
     */
    public function criar()
    {
        return $this->view('form');
    }

    /**
     * Formulário para editar contato com seus telefones
     */
    public function editar($dados)
    {
        try {
            $id       = (int) $dados['id'];
            $contato  = Contato::find($id);

            if (!$contato) {
                throw new RuntimeException("Contato #$id não encontrado.");
            }

            $telefones = Telefone::findByContato($id);
            return $this->view('form', [
                'contato'   => $contato,
                'telefones' => $telefones,
            ]);

        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    /**
     * Salva novo contato com seus telefones
     */
    public function salvar()
    {
        try {
            $this->validar(['nome']);

            $contato           = new Contato;
            $contato->nome     = $this->request->nome;
            $contato->email    = $this->request->email;
            $contato->save();

            // Salva os telefones enviados pelo formulário
            $this->salvarTelefones((int) $contato->id);

            $this->flash('sucesso', 'Contato salvo com sucesso!');
            $this->redirecionar('?controller=ContatosController&method=listar');

        } catch (RuntimeException $e) {
            return $this->view('form', ['erro' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza contato e seus telefones
     */
    public function atualizar($dados)
    {
        try {
            $id      = (int) $dados['id'];
            $contato = Contato::find($id);

            if (!$contato) {
                throw new RuntimeException("Contato #$id não encontrado.");
            }

            $this->validar(['nome']);

            $contato->nome  = $this->request->nome;
            $contato->email = $this->request->email;
            $contato->save();

            // Remove telefones antigos e salva os novos
            Telefone::destroyByContato($id);
            $this->salvarTelefones($id);

            $this->flash('sucesso', 'Contato atualizado com sucesso!');
            $this->redirecionar('?controller=ContatosController&method=listar');

        } catch (RuntimeException $e) {
            $contato = Contato::find((int) $dados['id']);
            return $this->view('form', ['contato' => $contato, 'erro' => $e->getMessage()]);
        }
    }

    /**
     * Exclusão lógica do contato e seus telefones
     */
    public function excluir($dados)
    {
        try {
            $id = (int) $dados['id'];
            Telefone::destroyByContato($id);
            Contato::destroy($id);

            $this->flash('sucesso', 'Contato excluído com sucesso!');
            $this->redirecionar('?controller=ContatosController&method=listar');

        } catch (RuntimeException $e) {
            $this->exibirErro($e->getMessage());
        }
    }

    // -------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------

    /**
     * Salva os telefones do formulário vinculados ao contato
     */
    private function salvarTelefones(int $contato_id): void
    {
        $telefones = $_POST['telefones'] ?? [];
        foreach ($telefones as $numero) {
            $numero = trim($numero);
            if (!empty($numero)) {
                $tel             = new Telefone;
                $tel->contato_id = $contato_id;
                $tel->telefone   = $numero;
                $tel->save();
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
    }

    private function exibirErro(string $mensagem): void
    {
        echo '<div class="container mt-3">';
        echo '<div class="alert alert-danger">' . htmlspecialchars($mensagem) . '</div>';
        echo '<a href="?controller=ContatosController&method=listar" class="btn btn-secondary">Voltar</a>';
        echo '</div>';
    }
}
