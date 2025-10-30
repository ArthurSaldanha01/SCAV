<?php

namespace App\Controller;

use App\Repository\ViagemRepository;
use App\Repository\VeiculoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class DashboardController
{
    private PhpRenderer $view;
    private ViagemRepository $viagemRepo;
    private VeiculoRepository $veiculoRepo;

    public function __construct(
        PhpRenderer $view,
        ViagemRepository $viagemRepo,
        VeiculoRepository $veiculoRepo
    ) {
        $this->view = $view;
        $this->viagemRepo = $viagemRepo;
        $this->veiculoRepo = $veiculoRepo;
    }

    public function index(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dados = [
            'nomeUsuario' => $_SESSION['user_name'] ?? 'Utilizador',
            'perfilUsuario' => $_SESSION['user_profile'] ?? null
        ];

        $viagensHoje = $this->viagemRepo->countViagensParaHoje();
        $proximaSaida = $this->viagemRepo->findProximaSaidaDeHoje();
        
        $totalVeiculos = $this->veiculoRepo->countAllVeiculos();

        $proximasViagens = $this->viagemRepo->findProximasViagensAgendadas(3);

        $stats = [
            'viagens_hoje' => $viagensHoje,
            'veiculos_total' => $totalVeiculos,
            
            'proxima_saida_horario' => $proximaSaida ? date('H:i', strtotime($proximaSaida['created_at'])) : 'N/A',
            'proxima_saida_placa' => $proximaSaida ? $proximaSaida['placa'] : 'N/A',
        ];

        $dados['stats'] = $stats;
        $dados['proximasViagens'] = $proximasViagens;
        
        return $this->view->render($response, 'dashboard.php', $dados);
    }
}

