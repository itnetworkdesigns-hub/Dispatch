<?php

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Register a new user: returns inserted user id on success
    public function register(string $name, string $email, string $password, string $role = 'trucker')
    {
        if (!in_array($role, ['trucker', 'supplier'], true)) {
            throw new InvalidArgumentException('Invalid role');
        }

        if ($this->findByEmail($email)) {
            return false; // email already exists
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :hash, :role)');
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':hash' => $hash,
            ':role' => $role,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    // Attempt login: returns user array on success or false
    public function login(string $email, string $password)
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        // remove password_hash before returning
        unset($user['password_hash']);
        return $user;
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password_hash, role, created_at FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: false;
    }

    // Optional helper: get user by ID
    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: false;
    }
}
