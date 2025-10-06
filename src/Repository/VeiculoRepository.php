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
        $stmt = $this->db->query('SELECT * FROM veiculos ORDER BY modelo');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM veiculos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO veiculos (placa, modelo, isOficial) VALUES (:placa, :modelo, :isOficial)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'placa' => strtoupper($data['placa']),
            'modelo' => $data['modelo'],
            'isOficial' => $data['isOficial'] ? 1 : 0,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE veiculos SET placa = :placa, modelo = :modelo, isOficial = :isOficial WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'placa' => strtoupper($data['placa']),
            'modelo' => $data['modelo'],
            'isOficial' => $data['isOficial'] ? 1 : 0,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM veiculos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}