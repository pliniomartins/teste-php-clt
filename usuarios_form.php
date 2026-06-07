<div class="container mt-4">

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo htmlspecialchars($erro); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <form action="?controller=UsuariosController&<?php
        echo isset($usuario->id)
            ? "method=atualizar&id={$usuario->id}"
            : "method=salvar";
    ?>" method="post">

        <div class="card mt-3">
            <div class="card-header">
                <strong><?php echo isset($usuario->id) ? 'Editar Usuário' : 'Novo Usuário'; ?></strong>
            </div>

            <div class="card-body">
                <!-- Nome -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Nome: *</label>
                    <input type="text" class="form-control col-sm-8" name="nome"
                           value="<?php echo htmlspecialchars($usuario->nome ?? ''); ?>" required />
                </div>

                <!-- Email -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Email: *</label>
                    <input type="email" class="form-control col-sm-8" name="email"
                           value="<?php echo htmlspecialchars($usuario->email ?? ''); ?>" required />
                </div>

                <?php if (!isset($usuario->id)): ?>
                <!-- Senha -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Senha: *</label>
                    <input type="password" class="form-control col-sm-8" name="senha" minlength="6" required />
                    <small class="col-sm-8 offset-sm-2 text-muted">Mínimo 6 caracteres.</small>
                </div>

                <!-- Confirmar Senha -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Confirmar Senha: *</label>
                    <input type="password" class="form-control col-sm-8" name="confirmar_senha" minlength="6" required />
                </div>
                <?php endif; ?>

                <!-- Ativo -->
                <div class="form-group form-row">
                    <label class="col-sm-2 col-form-label text-right">Ativo:</label>
                    <div class="col-sm-8 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ativo" value="1"
                                <?php echo (!isset($usuario->ativo) || $usuario->ativo) ? 'checked' : ''; ?>>
                            <label class="form-check-label">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ativo" value="0"
                                <?php echo (isset($usuario->ativo) && !$usuario->ativo) ? 'checked' : ''; ?>>
                            <label class="form-check-label">Não</label>
                        </div>
                    </div>
                </div>

                <hr>
                <h6 class="text-muted"><i class="fas fa-map-marker-alt"></i> Endereços</h6>

                <!-- Endereços existentes -->
                <?php if (!empty($enderecos)): ?>
                    <?php foreach ($enderecos as $end): ?>
                        <div class="alert alert-light border mb-2">
                            <strong><?php echo htmlspecialchars($end->logradouro); ?>, <?php echo htmlspecialchars($end->numero); ?></strong>
                            — <?php echo htmlspecialchars($end->bairro); ?>,
                            <?php echo htmlspecialchars($end->cidade); ?>/<?php echo htmlspecialchars($end->estado); ?>
                            — CEP: <?php echo htmlspecialchars($end->cep); ?>
                            <a href="?controller=UsuariosController&method=excluirEndereco&id=<?php echo $end->id; ?>&usuario_id=<?php echo $usuario->id; ?>"
                               class="btn btn-danger btn-sm float-right"
                               onclick="return confirm('Remover este endereço?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Formulário para adicionar endereço -->
                <div id="enderecos-container">
                    <div class="card border-dashed mb-2 endereco-item">
                        <div class="card-body py-2">
                            <div class="form-row mb-2">
                                <div class="col-sm-3">
                                    <label>CEP:</label>
                                    <input type="text" class="form-control cep-input" name="enderecos[0][cep]"
                                           placeholder="00000-000" maxlength="9" />
                                </div>
                                <div class="col-sm-7">
                                    <label>Logradouro:</label>
                                    <input type="text" class="form-control" name="enderecos[0][logradouro]"
                                           placeholder="Preenchido automaticamente" readonly />
                                </div>
                                <div class="col-sm-2">
                                    <label>Número:</label>
                                    <input type="text" class="form-control" name="enderecos[0][numero]"
                                           placeholder="Nº" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-4">
                                    <label>Bairro:</label>
                                    <input type="text" class="form-control" name="enderecos[0][bairro]"
                                           placeholder="Preenchido automaticamente" readonly />
                                </div>
                                <div class="col-sm-4">
                                    <label>Cidade:</label>
                                    <input type="text" class="form-control" name="enderecos[0][cidade]"
                                           placeholder="Preenchido automaticamente" readonly />
                                </div>
                                <div class="col-sm-2">
                                    <label>Estado:</label>
                                    <input type="text" class="form-control" name="enderecos[0][estado]"
                                           placeholder="UF" readonly maxlength="2" />
                                </div>
                                <div class="col-sm-2">
                                    <label>Complemento:</label>
                                    <input type="text" class="form-control" name="enderecos[0][complemento]"
                                           placeholder="Apto, Sala..." />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-success btn-sm mt-1" id="adicionar-endereco">
                    <i class="fas fa-plus"></i> Adicionar endereço
                </button>
            </div>

            <div class="card-footer">
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-save"></i> Salvar
                </button>
                <a class="btn btn-danger" href="?controller=UsuariosController&method=listar">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    // Busca CEP na API ViaCEP
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('cep-input')) {
            var cep = e.target.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch('https://viacep.com.br/ws/' + cep + '/json/')
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (!data.erro) {
                            var card = e.target.closest('.endereco-item');
                            card.querySelector('[name*="[logradouro]"]').value = data.logradouro || '';
                            card.querySelector('[name*="[bairro]"]').value     = data.bairro     || '';
                            card.querySelector('[name*="[cidade]"]').value     = data.localidade  || '';
                            card.querySelector('[name*="[estado]"]').value     = data.uf          || '';
                            card.querySelector('[name*="[numero]"]').focus();
                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(function () {
                        alert('Erro ao buscar CEP. Verifique sua conexão.');
                    });
            }
        }
    });

    // Adiciona novo bloco de endereço dinamicamente
    var enderecoIndex = 1;
    document.getElementById('adicionar-endereco').addEventListener('click', function () {
        var container = document.getElementById('enderecos-container');
        var idx       = enderecoIndex++;
        var div       = document.createElement('div');
        div.className = 'card border mb-2 endereco-item';
        div.innerHTML = `
            <div class="card-body py-2">
                <div class="form-row mb-2">
                    <div class="col-sm-3">
                        <label>CEP:</label>
                        <input type="text" class="form-control cep-input" name="enderecos[${idx}][cep]"
                               placeholder="00000-000" maxlength="9" />
                    </div>
                    <div class="col-sm-7">
                        <label>Logradouro:</label>
                        <input type="text" class="form-control" name="enderecos[${idx}][logradouro]"
                               placeholder="Preenchido automaticamente" readonly />
                    </div>
                    <div class="col-sm-2">
                        <label>Número:</label>
                        <input type="text" class="form-control" name="enderecos[${idx}][numero]" placeholder="Nº" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-sm-4">
                        <label>Bairro:</label>
                        <input type="text" class="form-control" name="enderecos[${idx}][bairro]"
                               placeholder="Preenchido automaticamente" readonly />
                    </div>
                    <div class="col-sm-4">
                        <label>Cidade:</label>
                        <input type="text" class="form-control" name="enderecos[${idx}][cidade]"
                               placeholder="Preenchido automaticamente" readonly />
                    </div>
                    <div class="col-sm-2">
                        <label>Estado:</label>
                        <input type="text" class="form-control" name="enderecos[${idx}][estado]"
                               placeholder="UF" readonly maxlength="2" />
                    </div>
                    <div class="col-sm-2">
                        <label>Complemento:</label>
                        <input type="text" class="form-control" name="enderecos[${idx}][complemento]"
                               placeholder="Apto, Sala..." />
                    </div>
                </div>
                <div class="text-right mt-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remover-endereco">
                        <i class="fas fa-trash"></i> Remover endereço
                    </button>
                </div>
            </div>`;
        container.appendChild(div);
    });

    // Remove bloco de endereço
    document.getElementById('enderecos-container').addEventListener('click', function (e) {
        if (e.target.closest('.remover-endereco')) {
            e.target.closest('.endereco-item').remove();
        }
    });
</script>
