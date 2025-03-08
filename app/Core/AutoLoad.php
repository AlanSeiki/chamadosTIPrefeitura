<?php
// Defina os caminhos das pastas
define('CONTROLLER_PATH', DIRETORIO . '/app/Controllers/');
define('MODEL_PATH', DIRETORIO . '/app/Models/');

spl_autoload_register(function ($class) {
    // Caminhos possíveis para a classe
    $paths = [
        CONTROLLER_PATH . $class . '.php',
        MODEL_PATH . $class . '.php',
    ];

    // Tenta incluir a classe de um dos caminhos
    foreach ($paths as $arquivo) {
        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }

    // Caso não encontre, loga e lança a exceção
    error_log("Classe $class não encontrada em " . implode(", ", $paths), 3, DIRETORIO . '/storage/logs/error.log');
    throw new Exception("Classe $class não encontrada em " . implode(", ", $paths));
});
