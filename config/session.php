<?php
session_start([
    'cookie_lifetime' => 3600, // Tempo de vida do cookie (1 hora)
    'cookie_httponly' => true, // Impede acesso via JavaScript (protege contra XSS)
    'cookie_secure' => isset($_SERVER['HTTPS']), // Apenas HTTPS
    'use_strict_mode' => true, // Impede reutilização de IDs antigos
    'use_only_cookies' => true, // Impede o uso de sessões via URL
]);
