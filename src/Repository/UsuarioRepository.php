<?php

namespace App\Repository;

use PDO;

class UsuarioRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT id, nome, email, perfil FROM usuarios ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nome, email, perfil FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES (:nome, :email, :senha_hash, :perfil)";
        $stmt = $this->db->prepare($sql);

        // Gera o hash da senha para armazenamento seguro
        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

        return $stmt->execute([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha_hash' => $senhaHash,
            'perfil' => $data['perfil'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, perfil = :perfil WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'nome' => $data['nome'],
            'email' => $data['email'],
            'perfil' => $data['perfil'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM usuarios WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
