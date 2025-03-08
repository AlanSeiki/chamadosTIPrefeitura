<?php

Class Controller {
    
    function view($content, $layout, $data = []) {
        extract($data);
        $content = DIRETORIO . "/app/Views/$content/index.php";
        include DIRETORIO . "/app/Views/layouts/$layout.php";
    }

    function retornoData($data = []) {
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    }

    function retornoPaginado($data = [], $totalRegistros = 1, $totalFiltrados = 1) {
        header('Content-Type: application/json');
        echo json_encode([
            "recordsTotal" => $totalRegistros, 
            "recordsFiltered" => $totalFiltrados, 
            "data" => $data
        ]);
        
    }

    function retornoMessage($message, $status) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode(['mensagem' => $message]);
    }
}