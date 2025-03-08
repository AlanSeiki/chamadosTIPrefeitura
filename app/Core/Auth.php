<?php
require_once DIRETORIO . '/config/session.php';

class Auth {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new User();
    }

    /**
     * Realiza o login do usuário
     */
    public function login($email, $password) {
        $query = $this->usuarioModel->query();
        $query->select('usuarios', 'usuarios.* ,cidades.nome as cidade, estados.nome as estado')
        ->leftJoin('cidades', 'cidades.id_cidade = usuarios.cidade_id')
        ->leftJoin('estados', 'estados.id_estado = usuarios.estado_id');
        $query->where('email','=',$email);
        $query->whereIsNull('email_code');
        $user = $query->first();
        

        if (!$user) {
            return ['status' => false, 'message' => 'Usuário não encontrado.'];
        }

        if (!password_verify($password, $user['senha'])) {
            return ['status' => false, 'message' => 'Senha incorreta.'];
        }

        session_regenerate_id(true);

        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['foto'] = $user['foto'];
        $_SESSION['tipo_user'] = $user['tipo_usuario'];
        $_SESSION['logged_in'] = time();

        return ['status' => true, 'message' => 'Login bem-sucedido.'];
    }

    /**
     * Realiza o logout do usuário
     */
    public function logout() {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        return ['status' => true, 'message' => 'Logout realizado com sucesso.'];
    }

    /**
     * Verifica se o usuário está logado
     */
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Obtém os dados do usuário autenticado
     */
    public static function user() {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'tipo_user' => $_SESSION['tipo_user'],
        ];
    }
}
?>
