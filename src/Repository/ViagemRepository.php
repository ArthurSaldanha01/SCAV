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

    // Você pode adicionar os métodos findById, update e delete aqui seguindo a mesma lógica.
}
