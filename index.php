<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', true);

spl_autoload_register(function ($class) {
    if (file_exists("$class.php")) {
        require_once "$class.php";
        return true;
    }
});
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Agenda de Contatos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <style>
        .accordion .card-header { cursor: pointer; }
        .accordion .card-header:hover { background-color: #f0f0f0; }
        .seta { float: right; transition: transform 0.3s; }
        .collapsed .seta { transform: rotate(-90deg); }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-address-book"></i> Agenda de Contatos
        </a>
    </nav>

    <div class="mt-3">
        <?php if ($_GET): ?>
            <?php
            $controller = isset($_GET['controller'])
                ? (class_exists($_GET['controller']) ? new $_GET['controller'] : null)
                : null;
            $method = $_GET['method'] ?? null;

            if ($controller && $method) {
                if (method_exists($controller, $method)) {
                    $parameters = $_GET;
                    unset($parameters['controller'], $parameters['method']);
                    call_user_func([$controller, $method], $parameters);
                } else {
                    echo '<div class="container"><div class="alert alert-danger">Método não encontrado!</div></div>';
                }
            } else {
                echo '<div class="container"><div class="alert alert-danger">Controller não encontrado!</div></div>';
            }
            ?>
        <?php else: ?>
            <!-- Tela inicial com accordion -->
            <div class="container mt-5">
                <h1 class="text-center mb-4">
                    <i class="fas fa-address-book"></i> Agenda de Contatos
                </h1>
                <hr>

                <div class="accordion" id="menuAccordion">

                    <!-- Contatos -->
                    <div class="card">
                        <div class="card-header" id="headingContatos"
                             data-toggle="collapse"
                             data-target="#collapseContatos"
                             aria-expanded="false">
                            <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-address-book text-success"></i> &nbsp;Contatos</span>
                                <i class="fas fa-chevron-down seta"></i>
                            </h5>
                        </div>
                        <div id="collapseContatos" class="collapse"
                             aria-labelledby="headingContatos"
                             data-parent="#menuAccordion">
                            <div class="card-body">
                                <p class="text-muted">Gerencie sua lista de contatos.</p>
                                <a href="?controller=ContatosController&method=listar"
                                   class="btn btn-success">
                                    <i class="fas fa-list"></i> Ver Contatos
                                </a>
                                <a href="?controller=ContatosController&method=criar"
                                   class="btn btn-outline-success ml-2">
                                    <i class="fas fa-plus"></i> Novo Contato
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Usuários -->
                    <div class="card">
                        <div class="card-header" id="headingUsuarios"
                             data-toggle="collapse"
                             data-target="#collapseUsuarios"
                             aria-expanded="false">
                            <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-users text-info"></i> &nbsp;Usuários</span>
                                <i class="fas fa-chevron-down seta"></i>
                            </h5>
                        </div>
                        <div id="collapseUsuarios" class="collapse"
                             aria-labelledby="headingUsuarios"
                             data-parent="#menuAccordion">
                            <div class="card-body">
                                <p class="text-muted">Gerencie os usuários do sistema.</p>
                                <a href="?controller=UsuariosController&method=listar"
                                   class="btn btn-info">
                                    <i class="fas fa-list"></i> Ver Usuários
                                </a>
                                <a href="?controller=UsuariosController&method=criar"
                                   class="btn btn-outline-info ml-2">
                                    <i class="fas fa-plus"></i> Novo Usuário
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
