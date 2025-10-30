<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Controller\LoginController;
use App\Controller\AuthController;
use App\Controller\DashboardController;
use App\Controller\VeiculoController;
use App\Controller\MotoristaController;
use App\Controller\ViagemController;
use App\Controller\UsuarioController;
use App\Controller\PortariaController;
use App\Controller\RelatorioController;
use App\Controller\AcessoController;
use App\Controller\AuditoriaController;

return function (App $app) {

    $app->get('/login', [LoginController::class, 'showLoginForm'])->setName('login.form');
    $app->post('/login', [LoginController::class, 'login'])->setName('login.submit');
    $app->get('/logout', [AuthController::class, 'logout'])->setName('logout');

    $app->get('/', function (Request $request, Response $response) {
        return $response->withHeader('Location', '/scav/public/login')->withStatus(302);
    });

    $app->get('/dashboard', [DashboardController::class, 'index'])->setName('dashboard');

    $app->get('/portaria/monitor', [PortariaController::class, 'monitorSaidas'])->setName('portaria.monitor');
    $app->get('/portaria/api/viagens-hoje', [PortariaController::class, 'getViagensHojeJson'])->setName('portaria.api.viagens');
    
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

    $app->group('/viagens', function (RouteCollectorProxy $group) {
        $group->get('', [ViagemController::class, 'index'])->setName('viagens.index');
        $group->get('/novo', [ViagemController::class, 'create'])->setName('viagens.create');
        $group->post('', [ViagemController::class, 'store'])->setName('viagens.store');
        $group->post('/{id}/cancelar', [ViagemController::class, 'cancelar'])->setName('viagens.cancelar');
    }); 

    $app->group('/usuarios', function (RouteCollectorProxy $group) {
        $group->get('', [UsuarioController::class, 'index'])->setName('usuarios.index');
        $group->get('/novo', [UsuarioController::class, 'create'])->setName('usuarios.create');
        $group->post('', [UsuarioController::class, 'store'])->setName('usuarios.store');
        $group->get('/{id}/edit', [UsuarioController::class, 'edit'])->setName('usuarios.edit');
        $group->post('/{id}/update', [UsuarioController::class, 'update'])->setName('usuarios.update');
        $group->post('/{id}/delete', [UsuarioController::class, 'destroy'])->setName('usuarios.destroy');
    });

    $app->group('/relatorios', function (RouteCollectorProxy $group) {
        $group->get('', [RelatorioController::class, 'index'])->setName('relatorios.index');
        $group->post('/exportar/csv', [RelatorioController::class, 'exportarCsv'])->setName('relatorios.export.csv');
    });

    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        $group->post('/registrar-acesso', [AcessoController::class, 'registrar'])->setName('api.acesso.registrar');
    });

    $app->get('/auditoria', [AuditoriaController::class, 'index'])->setName('auditoria.index');

};

