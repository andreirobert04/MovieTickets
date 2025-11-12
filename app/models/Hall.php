<?php

require_once __DIR__ . '/../core/BaseModel.php';

class Hall extends BaseModel
{
    public static function getAll(): array
    {
        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM halls ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM halls WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $hall = $stmt->fetch();
        return $hall ?: null;
    }
}
