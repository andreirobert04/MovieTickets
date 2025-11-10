<?php

require_once __DIR__ . '/../core/BaseModel.php';

class Movie extends BaseModel
{
    public static function getAll(): array
    {
        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM movies ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM movies WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $movie = $stmt->fetch();
        return $movie ?: null;
    }

}
