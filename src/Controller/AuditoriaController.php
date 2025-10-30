<?php

namespace App\Controller;

use App\Repository\AuditoriaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class AuditoriaController
{
    private PhpRenderer $view;
    private AuditoriaRepository $auditoriaRepo;

    public function __construct(PhpRenderer $view, AuditoriaRepository $auditoriaRepo)
    {
        $this->view = $view;
        $this->auditoriaRepo = $auditoriaRepo;
    }

    public function index(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (($_SESSION['user_profile'] ?? null) !== 'Administrador') {
            return $response->withHeader('Location', '/scav/public/dashboard')->withStatus(302);
        }

        $logs = $this->auditoriaRepo->findAll();

        return $this->view->render($response, 'auditoria/index.php', [
            'logs' => $logs
        ]);
    }
}
