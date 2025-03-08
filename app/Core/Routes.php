<?php

return [
    'POST' => [
        '/cadastro_usuario/create' => ['controller' => 'UserController', 'method' => 'create'],
        '/login' => ['controller' => 'LoginController', 'method' => 'login'],
        '/criar_chamados' => ['controller' => 'ChamadosController', 'method' => 'create', 'middleware' => ['authMiddleware']],
        '/logout' => ['controller' => 'LoginController', 'method' => 'logout',  'middleware' => ['authMiddleware']],
        '/mudar_status_chamado' => ['controller' => 'ChamadosController', 'method' => 'change_status',  'middleware' => ['authMiddleware']],
        '/atualiza_chamado' => ['controller' => 'ChamadosController', 'method' => 'updated',  'middleware' => ['authMiddleware']],
        '/atualizar_usuario' => ['controller' => 'UserController', 'method' => 'updated',  'middleware' => ['authMiddleware']],
    ],
    'GET' => [
        '/' => ['controller' => 'LoginController', 'method' => 'index'],
        '/home' => ['controller' => 'HomeController', 'method' => 'index', 'middleware' => ['authMiddleware']],
        '/dashboard' => ['controller' => 'HomeController', 'method' => 'dashboard', 'middleware' => ['authMiddleware']],
        '/chamados' => ['controller' => 'ChamadosController', 'method' => 'index', 'middleware' => ['authMiddleware']],
        '/form_criar_chamado' => ['controller' => 'ChamadosController', 'method' => 'formCriarCHamado', 'middleware' => ['authMiddleware']],
        '/cadastro_usuario' => ['controller' => 'UserController', 'method' => 'index'],
        '/get_cidades' => ['controller' => 'SelectController', 'method' => 'cidades'],
        '/get_tipo_incidentes' => ['controller' => 'SelectController', 'method' => 'tipo_incidentes',  'middleware' => ['authMiddleware']],
        '/get_chamados' => ['controller' => 'ChamadosController', 'method' => 'get',  'middleware' => ['authMiddleware']],
        '/visualizar_chamado' => ['controller' => 'ChamadosController', 'method' => 'getById',  'middleware' => ['authMiddleware']],
        '/perfil' => ['controller' => 'UserController', 'method' => 'perfil',  'middleware' => ['authMiddleware']],
        '/get_usuario' => ['controller' => 'UserController', 'method' => 'get',  'middleware' => ['authMiddleware']],
        '/get_estados' => ['controller' => 'SelectController', 'method' => 'estados'],
    ]
];
