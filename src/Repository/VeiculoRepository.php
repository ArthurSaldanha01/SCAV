<?php

namespace App\Repository;

use PDO;

class VeiculoRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM veiculos ORDER BY modelo ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM veiculos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO veiculos (placa, modelo, isOficial) 
                VALUES (:placa, :modelo, :isOficial)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'placa' => $data['placa'],
            'modelo' => $data['modelo'],
            'isOficial' => $data['isOficial'] ? 1 : 0,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE veiculos 
                SET placa = :placa, modelo = :modelo, isOficial = :isOficial
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'placa' => $data['placa'],
            'modelo' => $data['modelo'],
            'isOficial' => $data['isOficial'] ? 1 : 0,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM veiculos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    public function countAllVeiculos(): int
    {
        $sql = "SELECT COUNT(id) FROM veiculos WHERE isOficial = 1";
        $stmt = $this->db->query($sql);
        return (int)$stmt->fetchColumn();
    }

    public function countVeiculosPorStatus(string $status): int
    {
        return 0;
    }
}

