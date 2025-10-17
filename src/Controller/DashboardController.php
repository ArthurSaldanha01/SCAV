<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class DashboardController
{
    private PhpRenderer $view;

    public function __construct(PhpRenderer $view)
    {
        $this->view = $view;
    }

    /**
     * Exibe a página principal da dashboard, buscando dados da sessão.
     */
    public function index(Request $request, Response $response): Response
    {
        // Garante que a sessão PHP está iniciada para podermos ler os dados
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dados = [
            'nomeUsuario' => $_SESSION['user_name'] ?? 'Utilizador',
            'perfilUsuario' => $_SESSION['user_profile'] ?? null
        ];
        
        return $this->view->render($response, 'dashboard.php', $dados);
    }
}

