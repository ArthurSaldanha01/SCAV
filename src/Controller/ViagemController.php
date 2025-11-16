<?php

namespace App\Controller;

use App\Repository\MotoristaRepository;
use App\Repository\VeiculoRepository;
use App\Repository\ViagemRepository;
use App\Repository\AuditoriaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class ViagemController
{
    private PhpRenderer $view;
    private ViagemRepository $viagemRepo;
    private VeiculoRepository $veiculoRepo;
    private MotoristaRepository $motoristaRepo;
    private AuditoriaRepository $auditoriaRepo;

    public function __construct(
        PhpRenderer $view,
        ViagemRepository $viagemRepo,
        VeiculoRepository $veiculoRepo,
        MotoristaRepository $motoristaRepo,
        AuditoriaRepository $auditoriaRepo
    ) {
        $this->view = $view;
        $this->viagemRepo = $viagemRepo;
        $this->veiculoRepo = $veiculoRepo;
        $this->motoristaRepo = $motoristaRepo;
        $this->auditoriaRepo = $auditoriaRepo;
    }

    public function index(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        $estado = $params['estado'] ?? '';
        $paginaAtual = isset($params['page']) ? (int)$params['page'] : 1;
        if ($paginaAtual < 1) $paginaAtual = 1;

        $porPagina = 8;
        $offset = ($paginaAtual - 1) * $porPagina;

        $filtro = [];
        if ($estado !== '') {
            $filtro['status'] = $estado;
        }

        $totalRegistros = $this->viagemRepo->countFiltered($filtro);
        $totalPaginas = (int)ceil($totalRegistros / $porPagina);

        $viagens = $this->viagemRepo->findPaginatedFiltered($filtro, $porPagina, $offset);

        return $this->view->render($response, 'viagens/index.php', [
            'viagens' => $viagens,
            'totalPaginas' => $totalPaginas,
            'paginaAtual' => $paginaAtual
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
        $usuarioId = (int)($_SESSION['user_id'] ?? 0);
        $data['gestor_id'] = $usuarioId;

        if (empty($data['gestor_id']) || empty($data['veiculo_id']) || empty($data['motorista_id'])) {
            return $response->withHeader('Location', '/scav/public/viagens/novo')->withStatus(302);
        }
        
        $data['codigoAutorizacao'] = strtoupper(uniqid('SCAV-'));

        $this->viagemRepo->create($data);

        $detalhes = "Viagem autorizada. Código: {$data['codigoAutorizacao']}. Veículo ID: {$data['veiculo_id']}, Motorista ID: {$data['motorista_id']}.";
        $this->auditoriaRepo->registrar('CRIACAO_VIAGEM', $detalhes, $usuarioId);

        return $response->withHeader('Location', '/scav/public/viagens')->withStatus(302);
    }

    public function cancelar(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $response->withHeader('Location', '/scav/public/viagens')->withStatus(302);
        }

        $this->viagemRepo->updateStatus((int)$id, 'Cancelada');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuarioId = (int)($_SESSION['user_id'] ?? 0);
        $detalhes = "Viagem ID: {$id} foi cancelada.";
        $this->auditoriaRepo->registrar('CANCELAMENTO_VIAGEM', $detalhes, $usuarioId);

        return $response->withHeader('Location', '/scav/public/viagens')->withStatus(302);
    }
}
