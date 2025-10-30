<?php

namespace App\Controller;

use App\Repository\ViagemRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class PortariaController
{
  private PhpRenderer $view;
  private ViagemRepository $viagemRepo;

  public function __construct(PhpRenderer $view, ViagemRepository $viagemRepo)
  {
    $this->view = $view;
    $this->viagemRepo = $viagemRepo;
  }

  public function monitorSaidas(Request $request, Response $response): Response
  {
    $viagensHoje = $this->viagemRepo->findViagensAutorizadasParaHoje();

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf8', 'portuguese');

    $dataAtual = strftime('%d de %B de %Y'); 

    return $this->view->render($response, 'portaria/monitor.php', [
      'viagensHoje' => $viagensHoje,
      'dataAtual' => $dataAtual
    ]);
  }

  public function getViagensHojeJson(Request $request, Response $response): Response
  {
    $viagensHoje = $this->viagemRepo->findViagensAutorizadasParaHoje();
    
    $response->getBody()->write(json_encode($viagensHoje));
    return $response->withHeader('Content-Type', 'application/json');
  }
  
}
