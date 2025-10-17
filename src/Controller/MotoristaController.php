<?php

namespace App\Controller;

use App\Repository\MotoristaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class MotoristaController
{
    private PhpRenderer $view;
    private MotoristaRepository $repository;

    public function __construct(PhpRenderer $view, MotoristaRepository $repository)
    {
        $this->view = $view;
        $this->repository = $repository;
    }

    public function index(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $motoristas = $this->repository->findAll();

        return $this->view->render($response, 'motoristas/index.php', [
            'motoristas' => $motoristas,
            'perfilUsuario' => $_SESSION['user_profile'] ?? null
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'motoristas/create.php');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $this->repository->create($data);
        return $response->withHeader('Location', '/scav/public/motoristas')->withStatus(302);
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $motorista = $this->repository->findById($id);

        if (!$motorista) {
            return $response->withHeader('Location', '/scav/public/motoristas')->withStatus(404);
        }

        return $this->view->render($response, 'motoristas/edit.php', [
            'motorista' => $motorista
        ]);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $this->repository->update($id, $data);
        return $response->withHeader('Location', '/scav/public/motoristas')->withStatus(302);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $this->repository->delete($id);
        return $response->withHeader('Location', '/scav/public/motoristas')->withStatus(302);
    }
}

