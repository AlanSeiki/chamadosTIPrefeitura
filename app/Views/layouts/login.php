<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'eChamado' ?></title>
    <link rel="icon" type="image/png" href="/assets/img/comment-dots-regular.png">
    <link rel="stylesheet" href="/assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
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

    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/util.js"></script>
    <script src="/assets/vendor/select2/js/select2.min.js"></script>
    <?php include $content ?? 'erro404.php'; ?>

</body>

</html>