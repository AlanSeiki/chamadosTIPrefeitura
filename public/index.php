<?php
define('DIRETORIO', dirname(__DIR__));

require_once DIRETORIO.'/app/Core/Router.php';
require_once DIRETORIO.'/app/Core/Auth.php';
require_once DIRETORIO.'/app/Core/Middleware.php';
require_once DIRETORIO . '/app/Core/QueryBuilder.php';

router();
