<?php

declare(strict_types=1);
require_once(DIRETORIO . '/app/Helpers/functions.php');
require_once(DIRETORIO . '/app/Email/PHPMailer.php');
require_once(DIRETORIO . '/app/Email/SMTP.php');
require_once(DIRETORIO . '/app/Email/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController extends Controller
{

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new User();
    }

    public function index()
    {
        $this->view('usuarios', 'login');
    }

    public function perfil()
    {
        $this->view('perfil', 'default');
    }

    public function get()
    {        
        $this->retornoData(
            $this->usuarioModel->query()
            ->select('usuarios', 'usuarios.foto, usuarios.data_nascimento, usuarios.nome, usuarios.email, usuarios.telefone, usuarios.whatsapp, cidades.id_cidade, cidades.nome as cidade, estados.id_estado, estados.nome as estado')
            ->leftJoin('cidades', 'cidades.id_cidade = usuarios.cidade_id')
            ->leftJoin('estados', 'estados.id_estado = usuarios.estado_id')
            ->where('id_usuario', '=', Auth::user()['id'])
            ->first()
        );
    }

    function convertToDate($data) {
        // Verifica se a data está no formato DD/MM/YYYY
        $partesData = explode("/", $data);
        
        // Se a data for válida
        if (count($partesData) == 3) {
            // Retorna a data no formato YYYY-MM-DD
            return $partesData[2] . '-' . $partesData[1] . '-' . $partesData[0];
        }
    
        return null; // Retorna null se a data não for válida
    }

    function create()
    {
        //veriricar se existe alguem com este mesmo email
        $usuarioExistente = $this->usuarioModel->query()->select('usuarios', 'email')->where('email','=',teste_input($_POST["email"]))->first();
        if ($usuarioExistente != null) {
            return $this->retornoMessage('Email já está sendo usado.', 422);
        }
        // Captura e sanitiza os dados do formulário
        $token = bin2hex(random_bytes(32));
        $linkVerificacao = $this->gerarLinkEmail(teste_input($_POST["email"]), $token);
        $data = [
            'nome' => teste_input($_POST["nome"]),
            'data_nascimento' => $this->convertToDate(teste_input($_POST["data_nascimento"])),
            'email' => teste_input($_POST["email"]),
            'telefone' => teste_input($_POST["telefone"]),
            'whatsapp' => teste_input($_POST["whatsapp"]),
            'senha' => teste_input($_POST["senha"]),
            'cidade_id' => (int) teste_input($_POST["cidade"]),
            'estado_id' => (int) teste_input($_POST["estado"]),
            'email_code' => $token,
        ];
    
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->retornoMessage('Email inválido.', 422);
        }
    
        // Criptografar a senha
        $data['senha'] = password_hash($data['senha'], PASSWORD_BCRYPT);
    
        // Lidar com o upload da foto e converter para Base64
        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
            $mimeType = mime_content_type($_FILES["foto"]["tmp_name"]); // Descobrir o tipo real da imagem
            $extensoesPermitidas = ['image/jpeg', 'image/png', 'image/gif'];
        
            if (!in_array($mimeType, $extensoesPermitidas)) {
                return $this->retornoMessage("Formato de imagem inválido. Use JPG, PNG ou GIF.", 422);
            }
        
            // Converter para Base64
            $imagemData = file_get_contents($_FILES["foto"]["tmp_name"]);
            $base64Imagem = "data:{$mimeType};base64," . base64_encode($imagemData);
        
            $data["foto"] = $base64Imagem; // Salvar Base64 no banco
        } else {
            $data["foto"] = null;
        }
        
    
        // Inserir o usuário
        try {
            $this->usuarioModel->query()->create('usuarios', $data);
            $this->enviarEmailVerificacao(teste_input($_POST["email"]), $linkVerificacao);            

            return $this->retornoMessage('Email enviado para confirmação.', 201);
        } catch (\Throwable $th) {
            return $this->retornoMessage($th->getMessage(), 500);
        }
    }

    function updated() {
        $data = [
            'nome' => teste_input($_POST["nome"]),
            'data_nascimento' => $this->convertToDate(teste_input($_POST["data_nascimento"])),
            'email' => teste_input($_POST["email"]),
            'telefone' => teste_input($_POST["telefone"]),
            'whatsapp' => teste_input($_POST["whatsapp"]),
            'cidade_id' => (int) teste_input($_POST["cidade"]),
            'estado_id' => (int) teste_input($_POST["estado"]),
        ];
        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
            $mimeType = mime_content_type($_FILES["foto"]["tmp_name"]); // Descobrir o tipo real da imagem
            $extensoesPermitidas = ['image/jpeg', 'image/png', 'image/gif'];
        
            if (!in_array($mimeType, $extensoesPermitidas)) {
                return $this->retornoMessage("Formato de imagem inválido. Use JPG, PNG ou GIF.", 422);
            }
        
            // Converter para Base64
            $imagemData = file_get_contents($_FILES["foto"]["tmp_name"]);
            $base64Imagem = "data:{$mimeType};base64," . base64_encode($imagemData);
            $_SESSION['foto'] = $base64Imagem;
            $data["foto"] = $base64Imagem; // Salvar Base64 no banco
        } else {
            $data["foto"] = null;
        }
        $where = [
            "id_usuario" => $_SESSION['user_id']
        ];

        $_SESSION['user_name'] = teste_input($_POST["nome"]);
        

        $this->usuarioModel->query()->update('usuarios', $data, $where);

        return $this->retornoMessage('Dados Atualizados.', 200);
    }

    function enviarEmailVerificacao($email, $linkVerificacao) {
        try {
        
            // Configurar PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Exemplo: smtp.gmail.com
            $mail->SMTPAuth   = true;            
            $mail->Username   = 'seu email'; // Seu e-mail
            $mail->Password   = 'senha'; // Sua senha (ou app password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587; // Porta SMTP (Gmail usa 587 com TLS, 465 com SSL)
        
            // Configurar remetente e destinatário
            $mail->setFrom('suporte@echamado.com', 'eChamado'); // Quem está enviando
            $mail->addAddress($email); // Quem vai receber
        
            // Configurar e-mail (assunto, corpo, etc.)
            $mail->isHTML(true);
            $mail->Subject = "Bem-vindo ao eChamado";
            $mail->Body    = "Seu link de verificação: <a href='$linkVerificacao'>$linkVerificacao</a>";
            
            // Enviar e-mail
            $mail->send();

        } catch (Exception $e) {
            return $this->retornoMessage("Erro ao enviar e-mail: {$mail->ErrorInfo}", 500);
        }
    }

    function gerarLinkEmail($email, $token) {
       
        // Criar o link de verificação
        $baseUrl = "http://localhost:5000/";
        $link = $baseUrl . "?email=" . urlencode($email) . "&token=" . $token;
    
        return $link;
    }
}
