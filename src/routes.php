<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controller\VeiculoController;
use App\Controller\DashboardController;
use App\Controller\MotoristaController;

return function (App $app) {

    $app->get('/login', function (Request $request, Response $response) {
        return $this->get('view')->render($response, 'login.php');
    })->setName('login');

    $app->post('/login', function (Request $request, Response $response) {
        $dados = $request->getParsedBody();
        $email = $dados['usuario'] ?? '';
        $senha = $dados['senha'] ?? '';

        $db = $this->get('db');
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            return $response->withHeader('Location', '/scav/public/dashboard')->withStatus(302);
        }

        return $response->withHeader('Location', '/scav/public/login')->withStatus(302);
    });
    
    $app->get('/dashboard', [DashboardController::class, 'index'])->setName('dashboard');

    $app->get('/', function (Request $request, Response $response) {
        return $response->withHeader('Location', '/scav/public/login')->withStatus(302);
    });

    $app->group('/veiculos', function (RouteCollectorProxy $group) {
        
        $group->get('', [VeiculoController::class, 'index'])->setName('veiculos.index');
        
        $group->get('/novo', [VeiculoController::class, 'create'])->setName('veiculos.create');
        
        $group->post('', [VeiculoController::class, 'store'])->setName('veiculos.store');
        
        $group->get('/{id}/edit', [VeiculoController::class, 'edit'])->setName('veiculos.edit');
        
        $group->post('/{id}/update', [VeiculoController::class, 'update'])->setName('veiculos.update');
        
        $group->post('/{id}/delete', [VeiculoController::class, 'destroy'])->setName('veiculos.destroy');
        
    });

    $app->group('/motoristas', function (RouteCollectorProxy $group) {
        $group->get('', [MotoristaController::class, 'index'])->setName('motoristas.index');
        $group->get('/novo', [MotoristaController::class, 'create'])->setName('motoristas.create');
        $group->post('', [MotoristaController::class, 'store'])->setName('motoristas.store');
        $group->get('/{id}/edit', [MotoristaController::class, 'edit'])->setName('motoristas.edit');
        $group->post('/{id}/update', [MotoristaController::class, 'update'])->setName('motoristas.update');
        $group->post('/{id}/delete', [MotoristaController::class, 'destroy'])->setName('motoristas.destroy');
    });
};

