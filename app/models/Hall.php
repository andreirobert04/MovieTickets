<?php

require_once __DIR__ . '/../core/BaseModel.php';

class Hall extends BaseModel
{
    public static function getById(int $id): ?array
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM halls WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $hall = $stmt->fetch();
        return $hall ?: null;
    }
}
