<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class UsuarioController
{
    private PhpRenderer $view;
    private UsuarioRepository $repository;

    public function __construct(PhpRenderer $view, UsuarioRepository $repository)
    {
        $this->view = $view;
        $this->repository = $repository;
    }

    public function index(Request $request, Response $response): Response
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $usuarios = $this->repository->findAll();
        return $this->view->render($response, 'usuarios/index.php', [
            'usuarios' => $usuarios,
            'perfilUsuario' => $_SESSION['user_profile'] ?? null
        ]);
    }

    public function create(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'usuarios/create.php');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha']) || empty($data['perfil'])) {
            return $response->withHeader('Location', '/scav/public/usuarios/novo')->withStatus(302);
        }

        $this->repository->create($data);
        return $response->withHeader('Location', '/scav/public/usuarios')->withStatus(302);
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $usuario = $this->repository->findById($id);

        if (!$usuario) {
            return $response->withHeader('Location', '/scav/public/usuarios')->withStatus(404);
        }

        return $this->view->render($response, 'usuarios/edit.php', [
            'usuario' => $usuario
        ]);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $this->repository->update($id, $data);
        return $response->withHeader('Location', '/scav/public/usuarios')->withStatus(302);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $this->repository->delete($id);
        return $response->withHeader('Location', '/scav/public/usuarios')->withStatus(302);
    }
}