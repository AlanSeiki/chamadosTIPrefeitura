<?php

require 'AutoLoad.php';

function rotas()
{
    return require 'Routes.php';
}

function verificarUrlPresenteNoArray($uri, $rotas)
{
    return (array_key_exists($uri, $rotas)) ? $rotas[$uri] : [];
}


function router()
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $rotas = rotas();
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    $existeRota = verificarUrlPresenteNoArray($uri, $rotas[$requestMethod]);
    
    if (!empty($existeRota)) {
        if (!empty($existeRota['middleware'])) {
            foreach ($existeRota['middleware'] as $middleware) {
                AuthMiddleware::handle();
            }
        }
        if (isset($_SESSION['destroyed']) && $_SESSION['destroyed'] < time() - 300) {
            session_regenerate_id(true);
            $_SESSION['destroyed'] = time();
        }
        $controller = new $existeRota['controller']();
        $method = $existeRota['method'];
        $params = $existeRota['params'] ?? [];
        call_user_func_array([$controller, $method], $params);
    } else {
        http_response_code(404);
        throw new Exception('Algo deu errado');
    }
}
