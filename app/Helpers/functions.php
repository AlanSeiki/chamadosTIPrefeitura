<?php
function teste_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function hash_password(string $data) {
   return password_hash($data, PASSWORD_DEFAULT);
}

function verify_password(string $data, string $user_password) {
    return password_verify($data, $user_password);
 }