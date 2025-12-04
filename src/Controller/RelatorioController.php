<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class RelatorioController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(Request $request, Response $response): Response
    {
        return $response->withHeader('Location', '/scav/public/relatorios/acessos')->withStatus(302);
    }

    public function acessos(Request $request, Response $response): Response
    {
        date_default_timezone_set('America/Bahia');
        $q = $request->getQueryParams();
        $de = isset($q['de']) ? (new \DateTime($q['de']))->format('Y-m-d 00:00:00') : (new \DateTime('today'))->format('Y-m-d 00:00:00');
        $ate = isset($q['ate']) ? (new \DateTime($q['ate']))->format('Y-m-d 23:59:59') : (new \DateTime('today'))->format('Y-m-d 23:59:59');

        $stmt = $this->pdo->prepare("
            SELECT DATE(dataHora) dia, tipo, COUNT(*) qtd
            FROM registros_acesso
            WHERE dataHora BETWEEN ? AND ?
            GROUP BY DATE(dataHora), tipo
            ORDER BY dia ASC, tipo ASC
        ");
        $stmt->execute([$de, $ate]);
        $resumo = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = $this->pdo->prepare("
            SELECT 
                ra.dataHora,
                ra.placaDetectada,
                ra.tipo,
                ra.viagem_id,

                v.codigoAutorizacao,
                v.dataPrevista,
                ve.modelo AS veiculo_modelo,
                ve.placa  AS veiculo_placa,
                m.nome    AS motorista_nome
            FROM registros_acesso ra
            LEFT JOIN viagens   v   ON v.id = ra.viagem_id
            LEFT JOIN veiculos  ve  ON ve.id = v.veiculo_id
            LEFT JOIN motoristas m  ON m.id = v.motorista_id
            WHERE ra.dataHora BETWEEN ? AND ?
            ORDER BY ra.dataHora DESC
            LIMIT 500
        ");
        $stmt2->execute([$de, $ate]);
        $detalhes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        $DE = $de; $ATE = $ate; $RESUMO = $resumo; $DETALHES = $detalhes;
        require __DIR__ . '/../../templates/relatorios/acessos.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type','text/html');
    }


    public function acessosCsv(Request $request, Response $response): Response
    {
        date_default_timezone_set('America/Bahia');
        $q = $request->getQueryParams();
        $de = isset($q['de']) ? (new \DateTime($q['de']))->format('Y-m-d 00:00:00') : (new \DateTime('today'))->format('Y-m-d 00:00:00');
        $ate = isset($q['ate']) ? (new \DateTime($q['ate']))->format('Y-m-d 23:59:59') : (new \DateTime('today'))->format('Y-m-d 23:59:59');

        $stmt = $this->pdo->prepare("
            SELECT 
                ra.dataHora,
                ra.placaDetectada,
                ra.tipo,
                ra.viagem_id,
                v.codigoAutorizacao,
                v.dataPrevista,
                ve.modelo AS veiculo_modelo,
                ve.placa  AS veiculo_placa,
                m.nome    AS motorista_nome
            FROM registros_acesso ra
            LEFT JOIN viagens   v   ON v.id = ra.viagem_id
            LEFT JOIN veiculos  ve  ON ve.id = v.veiculo_id
            LEFT JOIN motoristas m  ON m.id = v.motorista_id
            WHERE ra.dataHora BETWEEN ? AND ?
            ORDER BY ra.dataHora DESC
        ");
        $stmt->execute([$de, $ate]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $csv = "dataHora,placa,tipo,viagem_id,codigoAutorizacao,veiculo_modelo,veiculo_placa,motorista_nome,dataPrevista\n";
        foreach ($rows as $r) {
            $diaHora = $r['dataHora'] ?? '';
            $placa = $r['placaDetectada'] ?? '';
            $tipo = $r['tipo'] ?? '';
            $viagemId = $r['viagem_id'] ?? '';
            $cod = $r['codigoAutorizacao'] ?? '';
            $modelo = $r['veiculo_modelo'] ?? '';
            $placaVeic = $r['veiculo_placa'] ?? '';
            $motorista = $r['motorista_nome'] ?? '';
            $dataPrev = $r['dataPrevista'] ?? '';
            $csv .= "{$diaHora},{$placa},{$tipo},{$viagemId},{$cod},{$modelo},{$placaVeic},{$motorista},{$dataPrev}\n";
        }

        $response->getBody()->write($csv);
        return $response->withHeader('Content-Type','text/csv');
    }


    public function acessosJson(Request $request, Response $response): Response
    {
        date_default_timezone_set('America/Bahia');
        $q = $request->getQueryParams();
        $de = isset($q['de']) ? (new \DateTime($q['de']))->format('Y-m-d 00:00:00') : (new \DateTime('today'))->format('Y-m-d 00:00:00');
        $ate = isset($q['ate']) ? (new \DateTime($q['ate']))->format('Y-m-d 23:59:59') : (new \DateTime('today'))->format('Y-m-d 23:59:59');

        $stmt = $this->pdo->prepare("SELECT DATE(dataHora) dia, tipo, COUNT(*) qtd FROM registros_acesso WHERE dataHora BETWEEN ? AND ? GROUP BY DATE(dataHora), tipo ORDER BY dia ASC, tipo ASC");
        $stmt->execute([$de, $ate]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode(['de'=>$de,'ate'=>$ate,'data'=>$rows]));
        return $response->withHeader('Content-Type','application/json');
    }

    public function exportarCsv(Request $request, Response $response): Response
    {
        return $this->acessosCsv($request, $response);
    }
}
