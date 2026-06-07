<div class="container mt-4">

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($erro); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <form action="?controller=ContatosController&<?php
        echo isset($contato->id)
            ? "method=atualizar&id={$contato->id}"
            : "method=salvar";
    ?>" method="post">

        <div class="card mt-3">
            <div class="card-header">
                <strong><?php echo isset($contato->id) ? 'Editar Contato' : 'Novo Contato'; ?></strong>
            </div>

            <div class="card-body">
                <!-- Nome -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Nome: *</label>
                    <input type="text" class="form-control col-sm-8" name="nome"
                           value="<?php echo htmlspecialchars($contato->nome ?? ''); ?>" required />
                </div>

                <!-- Email -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Email:</label>
                    <input type="email" class="form-control col-sm-8" name="email"
                           value="<?php echo htmlspecialchars($contato->email ?? ''); ?>" />
                </div>

                <!-- Telefones -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Telefones:</label>
                    <div class="col-sm-8" id="telefones-container">
                        <?php if (!empty($telefones)): ?>
                            <?php foreach ($telefones as $tel): ?>
                                <div class="input-group mb-2 telefone-item">
                                    <input type="text" class="form-control" name="telefones[]"
                                           value="<?php echo htmlspecialchars($tel->telefone); ?>" />
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-danger remover-telefone">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="input-group mb-2 telefone-item">
                                <input type="text" class="form-control" name="telefones[]"
                                       placeholder="(00) 00000-0000" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger remover-telefone">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Botão adicionar telefone -->
                <div class="form-group form-row">
                    <div class="col-sm-8 offset-sm-2">
                        <button type="button" class="btn btn-outline-success btn-sm" id="adicionar-telefone">
                            <i class="fas fa-plus"></i> Adicionar telefone
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-save"></i> Salvar
                </button>
                <a class="btn btn-danger" href="?controller=ContatosController&method=listar">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    // Adiciona novo campo de telefone dinamicamente
    document.getElementById('adicionar-telefone').addEventListener('click', function () {
        var container = document.getElementById('telefones-container');
        var div = document.createElement('div');
        div.className = 'input-group mb-2 telefone-item';
        div.innerHTML = `
            <input type="text" class="form-control" name="telefones[]" placeholder="(00) 00000-0000" />
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger remover-telefone">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
        container.appendChild(div);
    });

    // Remove campo de telefone
    document.getElementById('telefones-container').addEventListener('click', function (e) {
        if (e.target.closest('.remover-telefone')) {
            var items = document.querySelectorAll('.telefone-item');
            if (items.length > 1) {
                e.target.closest('.telefone-item').remove();
            }
        }
    });
</script>
