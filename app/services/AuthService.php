<?php

require_once __DIR__ . '/../config/config.php';

class AuthService
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function register(string $name, string $email, string $password): array
    {
        $pdo = getDB();

        $existing = self::findByEmail($email);
        if ($existing) {
            throw new Exception('Email deja folosit.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('
            INSERT INTO users (name, email, password_hash, role)
            VALUES (:name, :email, :hash, "user")
        ');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'hash' => $hash
        ]);

        $id = $pdo->lastInsertId();

        return [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => 'user'
        ];
    }

    public static function login(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        if (!$user) {
            return null;
        }

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'] ?? 'user',
            ];
            return $_SESSION['user'];
        }

        return null;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return !empty($_SESSION['user'])
            && ($_SESSION['user']['role'] ?? 'user') === 'admin';
    }
}
