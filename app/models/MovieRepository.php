<?php

require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/RepositoryInterface.php';

class MovieRepository extends BaseModel implements RepositoryInterface
{
    public function findAll(): array
    {
        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM movies ORDER BY title');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM movies WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $movie = $stmt->fetch();
        return $movie ?: null;
    }
}
