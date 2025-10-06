<?php

namespace App\Controller;

use App\Repository\VeiculoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class VeiculoController
{
    private PhpRenderer $view;
    private VeiculoRepository $repository;

    public function __construct(PhpRenderer $view, VeiculoRepository $repository)
    {
        $this->view = $view;
        $this->repository = $repository;
    }

    public function index(Request $request, Response $response): Response
    {
        $veiculos = $this->repository->findAll();
        return $this->view->render($response, 'veiculos/index.php', [
            'veiculos' => $veiculos
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

        return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $this->repository->delete($id);

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

        $data['isOficial'] = isset($data['isOficial']);

        $this->repository->update($id, $data);

        return $response->withHeader('Location', '/scav/public/veiculos')->withStatus(302);
    }
}