<?php

namespace App\Controller;

use App\Repository\VeiculoRepository;
use App\Repository\AuditoriaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class VeiculoController
{
    private PhpRenderer $view;
    private VeiculoRepository $repository;
    private AuditoriaRepository $auditoriaRepo;

    public function __construct(
        PhpRenderer $view, 
        VeiculoRepository $repository,
        AuditoriaRepository $auditoriaRepo
    ) {
        $this->view = $view;
        $this->repository = $repository;
        $this->auditoriaRepo = $auditoriaRepo;
    }

    public function index(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $veiculos = $this->repository->findAll();

        return $this->view->render($response, 'veiculos/index.php', [
            'veiculos' => $veiculos,
            'perfilUsuario' => $_SESSION['user_profile'] ?? null
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'veiculos/create.php');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if (empty($data['placa']) || empty($data['modelo'])) {
            return $response->withHeader('Location', '/scav/public/veiculos/novo')->withStatus(302);
        }

        $data['isOficial'] = isset($data['isOficial']);

        $this->repository->create($data);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $usuarioId = (int)($_SESSION['user_id'] ?? 0);
        $statusOficial = $data['isOficial'] ? 'OFICIAL' : 'NAO_OFICIAL';
        $detalhes = "Veículo criado. Placa: {$data['placa']}, Modelo: {$data['modelo']}. Marcado como: {$statusOficial}.";
        $this->auditoriaRepo->registrar('CRIACAO_VEICULO', $detalhes, $usuarioId);

        return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $this->repository->delete($id);
    
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $usuarioId = (int)($_SESSION['user_id'] ?? 0);
        $this->auditoriaRepo->registrar('EXCLUSAO_VEICULO', "Veículo ID: {$id} excluído.", $usuarioId);
        
        return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $veiculo = $this->repository->findById($id);

        if (!$veiculo) {
            return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
        }

        return $this->view->render($response, 'veiculos/edit.php', [
            'veiculo' => $veiculo
        ]);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();

        if (empty($data['placa']) || empty($data['modelo'])) {
            return $response->withHeader('Location', "/scav/public/veiculos/{$id}/edit")->withStatus(302);
        }

        $veiculoAntigo = $this->repository->findById($id);
        if (!$veiculoAntigo) {
             return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
        }
        $isOficialAntigo = (bool)$veiculoAntigo['isOficial'];
        
        $data['isOficial'] = isset($data['isOficial']);

        $this->repository->update($id, $data);

        if ($isOficialAntigo !== $data['isOficial']) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $usuarioId = (int)($_SESSION['user_id'] ?? 0);
            $statusNovo = $data['isOficial'] ? 'OFICIAL' : 'NAO_OFICIAL';
            $detalhes = "Marcação de veículo alterada. Veículo ID: {$id} (Placa: {$data['placa']}). Novo status: {$statusNovo}.";
            $this->auditoriaRepo->registrar('MARCACAO_VEICULO_OFICIAL', $detalhes, $usuarioId);
        }

        return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
    }
}

