<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'eChamado' ?></title>
    <link rel="icon" type="image/png" href="/assets/img/comment-dots-regular.png">

    <link rel="stylesheet" href="/assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/vendor/datatable/datatables.min.css">
    <link rel="stylesheet" href="/assets/vendor/select2/css/select2.min.css">
</head>

<body>
    <div id="errorToast" class="toast position-fixed config-toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <span class="text-white fs-6" id="messageError"></span>
        </div>
    </div>

    <div id="successToast" class="toast position-fixed config-toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <span class="text-white fs-6" id="messageSucesso"></span>
        </div>
    </div>

    <div id="warningToast" class="toast position-fixed config-toast text-bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <span class="text-white fs-6" id="messageAlerta"></span>
        </div>
    </div>

    <!-- Navbar fixa no topo -->
    <div class="navbar-top">
        <div class="d-flex gap-1">
            <h5>eChamado</h5>
            <img src="/assets/img/comment-dots-regular.png" height="20">
        </div>
        <ul class="nav sidebar">
            <li class="nav-item"><a href="/home" class="nav-link text-white"><i class="fas fa-home"></i> Home</a></li>
            <li class="nav-item"><a href="/chamados" class="nav-link text-white"><i class="fas fa-envelope"></i> Chamados</a></li>
            <?php if ($_SESSION["tipo_user"] == 'N') {?>
                <li class="nav-item"><a href="/perfil" class="nav-link text-white"><i class="fas fa-user"></i> Perfil</a></li>
            <?php  }?>                
        </ul>
        <div class="user-info" data-bs-toggle="collapse" href="#perfiltCollapse" role="button" aria-expanded="false" aria-controls="perfiltCollapse">
            <i class="bi bi-bell notification-icon text-white"></i>
            <span class="text-white"><?php echo $_SESSION["user_name"] ?></span>
            <img src="<?= isset($_SESSION["foto"]) && !empty($_SESSION["foto"]) ? $_SESSION["foto"] : '/assets/img/no-user.png'; ?>" 
     alt="Foto de perfil" class="min-photo-user">

        </div>        
    </div>
    <div class="collapse perfiltCollapse" id="perfiltCollapse">
        <div class="card card-body">
            <button type="button" class="btn btn-danger" id="logout">
                <i class="fas fa-sign-out-alt"></i> Sair
            </button>
        </div>
    </div>
    <div class="container-fluid">
        <script src="/assets/vendor/jquery/jquery.min.js"></script>
        <script src="/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/table.js"></script>
        <script src="/assets/js/util.js"></script>
        <script src="/assets/vendor/datatable/datatables.min.js"></script>
        <script src="/assets/vendor/select2/js/select2.min.js"></script>

        <!-- Conteúdo Principal -->
        <main class="container-padrao col-12">
            <?php include $content ?? 'erro404.php'; ?>
        </main>
    </div>

    <!-- Tab Bar Bottom (visível apenas no mobile) -->
    <div class="tabbar-bottom d-md-none">
        <a href="/home"><i class="fas fa-home tab-icon"></i></a>
        <a href="/chamados"><i class="fas fa-envelope tab-icon"></i></a>
        <?php if ($_SESSION["tipo_user"] == 'N') {?>
            <a href="/perfil" class="nav-link text-white"><i class="fas fa-user tab-icon"></i></a>
        <?php  } ?>
    </div>


</body>

</html>