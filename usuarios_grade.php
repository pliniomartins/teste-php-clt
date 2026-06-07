<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Usuários</h1>
        <div>
            <a href="?controller=UsuariosController&method=criar" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Novo Usuário
            </a>
            <a href="?controller=ContatosController&method=listar" class="btn btn-secondary btn-sm ml-1">
                <i class="fas fa-address-book"></i> Contatos
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
        <input type="hidden" name="controller" value="UsuariosController">
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
                    <a href="?controller=UsuariosController&method=listar" class="btn btn-outline-danger">
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
                <th>Email</th>
                <th>Ativo</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($usuarios): ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario->nome); ?></td>
                    <td><?php echo htmlspecialchars($usuario->email); ?></td>
                    <td>
                        <?php if ($usuario->ativo): ?>
                            <span class="badge badge-success">Sim</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Não</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $usuario->criado_em; ?></td>
                    <td>
                        <a href="?controller=UsuariosController&method=editar&id=<?php echo $usuario->id; ?>"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="?controller=UsuariosController&method=excluir&id=<?php echo $usuario->id; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Confirma a exclusão de <?php echo htmlspecialchars($usuario->nome); ?>?')">
                            <i class="fas fa-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <?php echo $busca ? "Nenhum usuário encontrado para \"$busca\"." : "Nenhum usuário cadastrado."; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
