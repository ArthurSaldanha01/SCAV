<?php
namespace App\Repository;

use PDO;

class RegistroAcessoRepository
{
    private PDO $pdo;
    private $patOld;
    private $patNew;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->patOld = '/^[A-Z]{3}[0-9]{4}$/';
        $this->patNew = '/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/';
    }

    public function normalizePlate(string $s): ?string
    {
        $s = strtoupper(str_replace(' ', '', $s));
        if (preg_match($this->patOld, $s) || preg_match($this->patNew, $s)) return $s;
        $t = str_split($s);
        if (count($t) >= 7) {
            foreach ([0,1,2,4] as $i) if (isset($t[$i])) $t[$i] = strtr($t[$i], ['0'=>'O','1'=>'I','2'=>'Z','5'=>'S','8'=>'B']);
            foreach ([3,5,6] as $i) if (isset($t[$i])) $t[$i] = strtr($t[$i], ['O'=>'0','I'=>'1','Z'=>'2','S'=>'5','B'=>'8','Q'=>'0']);
        }
        $s2 = implode('', $t);
        if (preg_match($this->patOld, $s2) || preg_match($this->patNew, $s2)) return $s2;
        return null;
    }

    public function isDuplicateRecent(string $placa, string $tipo, string $dataHora, int $ttlSec): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM registros_acesso WHERE placaDetectada=? AND tipo=? AND ABS(TIMESTAMPDIFF(SECOND, dataHora, ?))<=?");
        $stmt->execute([$placa, $tipo, $dataHora, $ttlSec]);
        return (bool)$stmt->fetchColumn();
    }

    public function matchViagemId(string $placa, string $dataHora): ?int
    {
        $sql = "
            SELECT v.id
            FROM viagens v
            JOIN veiculos ve ON ve.id = v.veiculo_id
            WHERE ve.placa = :placa
            AND DATE(v.dataPrevista) = DATE(:dataHora)
            AND (v.status IS NULL OR v.status = 'Autorizada')
            ORDER BY v.id DESC
            LIMIT 1
        ";

        $st = $this->pdo->prepare($sql);
        $st->execute([
            ':placa'    => $placa,
            ':dataHora' => $dataHora,
        ]);
        $id = $st->fetchColumn();

        return $id ? (int)$id : null;
    }

    public function insert(string $placa, string $dataHora, string $tipo, ?int $viagemId): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO registros_acesso (placaDetectada,dataHora,tipo,viagem_id,criado_em) VALUES (?,?,?,?,NOW())");
        $stmt->execute([$placa, $dataHora, $tipo, $viagemId]);
        return (int)$this->pdo->lastInsertId();
    }
}
