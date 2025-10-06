<?php

namespace App\Repository;

use PDO;

class MotoristaRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM motoristas ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM motoristas WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO motoristas (nome, cnh) VALUES (:nome, :cnh)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nome' => $data['nome'],
            'cnh' => $data['cnh'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE motoristas SET nome = :nome, cnh = :cnh, status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nome' => $data['nome'],
            'cnh' => $data['cnh'],
            'status' => $data['status'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM motoristas WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
