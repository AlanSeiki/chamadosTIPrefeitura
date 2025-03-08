<?php

class LoginController extends Controller
{
    private $auth;
    private $usuarioModel;

    public function __construct() {
        $this->auth = new Auth();
        $this->usuarioModel = new User();
    }

    public function index()
    {
        if (isset($_GET['email']) && isset($_GET['token'])) {
            $this->usuarioModel->query()->update('usuarios', ['email_code' => null], ['email' => $_GET['email'], 'email_code' =>  $_GET['token']]);
        }

        $this->view('login','login');
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                return $this->retornoMessage('E-mail e senha são obrigatórios.', 422);
            }
            
            $retorno = $this->auth->login($email,$senha);

            if ($retorno['status']) {
                return $this->retornoMessage('Bem vindo', 200);                
            } else {
                return $this->retornoMessage('E-mail ou senha incorretos.', 422);
            } 
            
        }
    }

    public function logout()
    {
        $this->auth->logout();
    }
}
