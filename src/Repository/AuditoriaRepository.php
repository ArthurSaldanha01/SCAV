<?php

namespace App\Repository;

use PDO;

class AuditoriaRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function registrar(string $acao, string $detalhes, int $usuarioId): bool
    {
        $sql = "INSERT INTO auditoria (acao, detalhes, dataHora, usuario_id) 
                VALUES (:acao, :detalhes, NOW(), :usuario_id)";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'acao' => $acao,
                'detalhes' => $detalhes,
                'usuario_id' => $usuarioId
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function findAll(): array
    {
        $sql = "
            SELECT 
                a.id, 
                a.acao, 
                a.detalhes, 
                a.dataHora, 
                u.nome as usuario_nome
            FROM auditoria a
            JOIN usuarios u ON a.usuario_id = u.id
            ORDER BY a.dataHora DESC
            LIMIT 100 -- Limita aos 100 registros mais recentes por performance
        ";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
