<?php

require_once __DIR__ . '/../core/BaseModel.php';

class Showtime extends BaseModel
{
    public static function getByMovie(int $movieId): array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('
            SELECT s.id, s.start_time, s.price,
                   h.name AS hall_name
            FROM showtimes s
            JOIN halls h ON s.hall_id = h.id
            WHERE s.movie_id = :movie_id
            ORDER BY s.start_time
        ');
        $stmt->execute(['movie_id' => $movieId]);
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $pdo = self::db();
        $stmt = $pdo->prepare('
            SELECT s.*, h.name AS hall_name, h.total_rows, h.seats_per_row
            FROM showtimes s
            JOIN halls h ON s.hall_id = h.id
            WHERE s.id = :id
        ');
        $stmt->execute(['id' => $id]);
        $showtime = $stmt->fetch();
        return $showtime ?: null;
    }
}
