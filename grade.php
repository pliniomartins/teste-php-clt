<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Contatos</h1>
        <div>
            <a href="?controller=ContatosController&method=criar" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Novo Contato
            </a>
            <a href="?controller=UsuariosController&method=listar" class="btn btn-info btn-sm ml-1">
                <i class="fas fa-users"></i> Usuários
            </a>
        </div>
    </div>
    <hr>

    <?php if (isset($flash)): ?>
        <div class="alert alert-<?php echo $flash['tipo'] === 'sucesso' ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($flash['mensagem']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Busca por nome -->
    <form method="get" action="" class="mb-3">
        <input type="hidden" name="controller" value="ContatosController">
        <input type="hidden" name="method" value="listar">
        <div class="input-group">
            <input type="text" class="form-control" name="busca"
                   placeholder="Buscar por nome..."
                   value="<?php echo htmlspecialchars($busca ?? ''); ?>">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <?php if (!empty($busca)): ?>
                    <a href="?controller=ContatosController&method=listar" class="btn btn-outline-danger">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($contatos): ?>
                <?php foreach ($contatos as $contato): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contato->nome); ?></td>
                    <td><?php echo htmlspecialchars($contato->telefone ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($contato->email ?? ''); ?></td>
                    <td>
                        <a href="?controller=ContatosController&method=editar&id=<?php echo $contato->id; ?>"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="?controller=ContatosController&method=excluir&id=<?php echo $contato->id; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Confirma a exclusão de <?php echo htmlspecialchars($contato->nome); ?>?')">
                            <i class="fas fa-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <?php echo $busca ? "Nenhum contato encontrado para \"$busca\"." : "Nenhum contato cadastrado."; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
