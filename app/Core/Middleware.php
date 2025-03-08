<?php

class AuthMiddleware extends Auth {
    public static function handle() {
        if (!Auth::check()) {
            header("Location: /");
            exit;
        }
    }
}
