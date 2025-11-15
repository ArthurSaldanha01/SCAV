<?php

namespace App\Repository;

use PDO;

class ViagemRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $sql = "
            SELECT 
                v.*, 
                u.nome as gestor_nome,
                veic.modelo as veiculo_modelo,
                veic.placa as veiculo_placa,
                m.nome as motorista_nome
            FROM viagens v
            JOIN usuarios u ON v.gestor_id = u.id
            JOIN veiculos veic ON v.veiculo_id = veic.id
            JOIN motoristas m ON v.motorista_id = m.id
            ORDER BY v.dataPrevista DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $codigoAutorizacao = mt_rand(100000, 999999);

        $sql = "INSERT INTO viagens 
                    (dataPrevista, finalidade, observacoes, codigoAutorizacao, gestor_id, veiculo_id, motorista_id) 
                VALUES 
                    (:dataPrevista, :finalidade, :observacoes, :codigoAutorizacao, :gestor_id, :veiculo_id, :motorista_id)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'dataPrevista' => $data['dataPrevista'],
            'finalidade' => $data['finalidade'],
            'observacoes' => $data['observacoes'],
            'codigoAutorizacao' => $codigoAutorizacao,
            'gestor_id' => $data['gestor_id'],
            'veiculo_id' => $data['veiculo_id'],
            'motorista_id' => $data['motorista_id'],
        ]);
    }

    public function findViagensAutorizadasParaHoje(): array
    {
        $hoje = date('Y-m-d');
        
        $sql = "
            SELECT 
                v.codigoAutorizacao,
                v.observacoes,
                ve.modelo AS veiculo_modelo,
                ve.placa AS veiculo_placa,
                m.nome AS motorista_nome
            FROM viagens v
            JOIN veiculos ve ON v.veiculo_id = ve.id
            JOIN motoristas m ON v.motorista_id = m.id
            WHERE v.dataPrevista = :hoje AND v.status = 'Autorizada'
            ORDER BY v.created_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['hoje' => $hoje]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): bool
    {
        try {
            $sql = "UPDATE viagens SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function countFiltered(array $filtro = []): int
    {
        $sql = "SELECT COUNT(v.id)
                FROM viagens v
                JOIN usuarios u ON v.gestor_id = u.id
                JOIN veiculos veic ON v.veiculo_id = veic.id
                JOIN motoristas m ON v.motorista_id = m.id
                WHERE 1=1";

        $params = [];

        if (!empty($filtro['status'])) {
            $sql .= " AND v.status = :status";
            $params['status'] = $filtro['status'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn();
    }

    public function findPaginatedFiltered(array $filtro, int $limit, int $offset): array
    {
        $sql = "
            SELECT 
                v.*, 
                u.nome as gestor_nome,
                veic.modelo as veiculo_modelo,
                veic.placa as veiculo_placa,
                m.nome as motorista_nome
            FROM viagens v
            JOIN usuarios u ON v.gestor_id = u.id
            JOIN veiculos veic ON v.veiculo_id = veic.id
            JOIN motoristas m ON v.motorista_id = m.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filtro['status'])) {
            $sql .= " AND v.status = :status";
            $params['status'] = $filtro['status'];
        }

        $sql .= " ORDER BY v.dataPrevista DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countViagensParaHoje(): int
    {
        $hoje = date('Y-m-d');
        $sql = "SELECT COUNT(id) FROM viagens WHERE dataPrevista = :hoje AND status != 'Cancelada'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['hoje' => $hoje]);
        return (int)$stmt->fetchColumn();
    }

    public function findProximaSaidaDeHoje(): ?array
    {
        $hoje = date('Y-m-d');
        $sql = "
            SELECT v.created_at, ve.placa 
            FROM viagens v
            JOIN veiculos ve ON v.veiculo_id = ve.id
            WHERE v.dataPrevista = :hoje AND v.status = 'Autorizada'
            ORDER BY v.created_at ASC 
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['hoje' => $hoje]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findProximasViagensAgendadas(int $limite = 3): array
    {
        $hoje = date('Y-m-d');
        $sql = "
            SELECT v.dataPrevista, v.finalidade, ve.modelo AS veiculo_modelo, ve.placa AS veiculo_placa
            FROM viagens v
            JOIN veiculos ve ON v.veiculo_id = ve.id
            WHERE v.dataPrevista >= :hoje AND v.status = 'Autorizada'
            ORDER BY v.dataPrevista ASC, v.created_at ASC
            LIMIT :limite
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':hoje', $hoje);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
