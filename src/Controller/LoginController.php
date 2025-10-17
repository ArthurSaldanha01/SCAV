<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use PDO;

class LoginController
{
    private PhpRenderer $view;
    private PDO $db;

    public function __construct(PhpRenderer $view, PDO $db)
    {
        $this->view = $view;
        $this->db = $db;
    }

    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'login.php');
    }

    /**
     * Processa os dados do formulário e tenta autenticar o usuário.
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $email = $data['usuario'] ?? '';
        $senha = $data['senha'] ?? '';

        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            // Sucesso no login
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Guarda as informações importantes do usuário na sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_profile'] = $usuario['perfil'];

            return $response->withHeader('Location', '/scav/public/dashboard')->withStatus(302);
        }

        return $response->withHeader('Location', '/scav/public/login')->withStatus(302);
    }
}