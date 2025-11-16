<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\RegistroAcessoRepository;

class AcessoController
{
    private RegistroAcessoRepository $repo;
    private string $token;

    public function __construct(RegistroAcessoRepository $repo, string $token)
    {
        $this->repo = $repo;
        $this->token = $token;
    }

    public function registrar(Request $request, Response $response): Response
    {
        date_default_timezone_set('America/Bahia');

        $raw = (string)$request->getBody();
        if ($raw === '') $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: [];
        $q = $request->getQueryParams();

        $auth = $request->getHeaderLine('Authorization');
        if (!$auth && isset($_SERVER['HTTP_AUTHORIZATION'])) $auth = $_SERVER['HTTP_AUTHORIZATION'];
        $postedToken = $data['token'] ?? ($q['token'] ?? null);
        $recvToken = null;
        if ($auth && preg_match('/Bearer\s+(.+)/i', $auth, $m)) $recvToken = trim($m[1]); elseif ($postedToken) $recvToken = trim($postedToken);
        if ($this->token !== (string)$recvToken) {
            $response->getBody()->write(json_encode(['error'=>'unauthorized']));
            return $response->withStatus(401)->withHeader('Content-Type','application/json');
        }

        $placa = $this->repo->normalizePlate((string)($data['placa'] ?? ($q['placa'] ?? '')));
        $tipo = strtoupper((string)($data['tipo'] ?? ($q['tipo'] ?? '')));
        $ts = (string)($data['timestamp'] ?? ($q['timestamp'] ?? ''));

        if (!$placa || !in_array($tipo, ['ENTRY','EXIT'], true)) {
            $response->getBody()->write(json_encode(['error'=>'invalid']));
            return $response->withStatus(422)->withHeader('Content-Type','application/json');
        }

        $dataHora = $ts ? (new \DateTime($ts))->format('Y-m-d H:i:s') : (new \DateTime('now'))->format('Y-m-d H:i:s');
        if ($this->repo->isDuplicateRecent($placa, $tipo, $dataHora, 5)) {
            $response->getBody()->write(json_encode(['ok'=>true,'dup'=>true]));
            return $response->withHeader('Content-Type','application/json');
        }

        $viagemId = $this->repo->matchViagemId($placa, $dataHora);
        $id = $this->repo->insert($placa, $dataHora, $tipo, $viagemId);

        $response->getBody()->write(json_encode(['ok'=>true,'id'=>$id,'viagem_id'=>$viagemId]));
        return $response->withHeader('Content-Type','application/json');
    }


}
