<?php

namespace App\Controller;

use App\Repository\MotoristaRepository;
use App\Repository\VeiculoRepository;
use App\Repository\ViagemRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class ViagemController
{
    private PhpRenderer $view;
    private ViagemRepository $viagemRepo;
    private VeiculoRepository $veiculoRepo;
    private MotoristaRepository $motoristaRepo;

    public function __construct(
        PhpRenderer $view,
        ViagemRepository $viagemRepo,
        VeiculoRepository $veiculoRepo,
        MotoristaRepository $motoristaRepo
    ) {
        $this->view = $view;
        $this->viagemRepo = $viagemRepo;
        $this->veiculoRepo = $veiculoRepo;
        $this->motoristaRepo = $motoristaRepo;
    }

    public function index(Request $request, Response $response): Response
    {
        $viagens = $this->viagemRepo->findAll();
        return $this->view->render($response, 'viagens/index.php', [
            'viagens' => $viagens
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        $veiculos = $this->veiculoRepo->findAll();
        $motoristas = $this->motoristaRepo->findAll();

        return $this->view->render($response, 'viagens/create.php', [
            'veiculos' => $veiculos,
            'motoristas' => $motoristas
        ]);
    }

    public function store(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $data = $request->getParsedBody();

        $data['gestor_id'] = $_SESSION['user_id'] ?? null;

        if (empty($data['gestor_id']) || empty($data['veiculo_id']) || empty($data['motorista_id'])) {

            return $response->withHeader('Location', '/scav/public/viagens/novo')->withStatus(302);
        }
        
        $data['codigoAutorizacao'] = strtoupper(uniqid('SCAV-'));

        $this->viagemRepo->create($data);

        return $response->withHeader('Location', '/scav/public/viagens')->withStatus(302);
    }
}

