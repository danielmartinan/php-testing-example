<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 * Repositorio para gestionar usuarios en la base de datos
 */
class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Crea un usuario en la base de datos
     */
    public function create(string $email, string $password): User
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        if (strlen($password) < 6) {
            throw new \InvalidArgumentException('Password must be at least 6 characters');
        }

        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([
            $email,
            password_hash($password, PASSWORD_BCRYPT),
        ]);

        $userId = (int) $this->pdo->lastInsertId();

        return new User(
            id: $userId,
            email: $email,
            password: password_hash($password, PASSWORD_BCRYPT),
            createdAt: new \DateTime(),
        );
    }

    /**
     * Busca un usuario por ID
     */
    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->hydrateUser($row);
    }

    /**
     * Busca un usuario por email
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->hydrateUser($row);
    }

    /**
     * Obtiene todos los usuarios
     *
     * @return User[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users ORDER BY created_at DESC');
        if ($stmt === false) {
            return [];
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => $this->hydrateUser($row), $rows);
    }

    /**
     * Actualiza un usuario
     */
    public function update(User $user): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
        $stmt->execute([$user->email, $user->id]);
    }

    /**
     * Elimina un usuario
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');

        return $stmt->execute([$id]);
    }

    /**
     * Cuenta el número total de usuarios
     */
    public function count(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) as count FROM users');
        if ($stmt === false) {
            return 0;
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return 0;
        }

        return (int) $result['count'];
    }

    /**
     * Verifica credenciales de login
     */
    public function validateCredentials(string $email, string $password): bool
    {
        $user = $this->findByEmail($email);

        if ($user === null) {
            return false;
        }

        return password_verify($password, $user->password);
    }

    /**
     * Convierte array a objeto User
     * @param array<string, mixed> $row
     */
    private function hydrateUser(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            email: $row['email'],
            password: $row['password'],
            createdAt: new \DateTime($row['created_at']),
        );
    }
}
