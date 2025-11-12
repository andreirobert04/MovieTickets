<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Hall.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/CSRFTokenService.php';

class ShowtimeController extends Controller
{
    private function requireAdmin(): void
    {
        if (!AuthService::isAdmin()) {
            http_response_code(403);
            echo "Access denied. Admin only.";
            exit;
        }
    }

    // formular adaugare proiectie pentru un film
    public function createForm(): void
    {
        $this->requireAdmin();

        $movieId = (int)($_GET['movie_id'] ?? 0);
        $movie = Movie::getById($movieId);

        if (!$movie) {
            echo "Filmul nu există.";
            return;
        }

        $halls = Hall::getAll();

        $this->render('showtime/create', [
            'movie' => $movie,
            'halls' => $halls,
        ]);
    }

    public function editForm(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo "Proiecție invalidă.";
            return;
        }

        $pdo = getDB();
        $stmt = $pdo->prepare('
            SELECT s.*, m.title AS movie_title, m.id AS movie_id
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.id
            WHERE s.id = :id
        ');
        $stmt->execute(['id' => $id]);
        $showtime = $stmt->fetch();

        if (!$showtime) {
            echo "Proiecția nu există.";
            return;
        }

        $halls = Hall::getAll();

        $this->render('showtime/edit', [
            'showtime' => $showtime,
            'halls' => $halls,
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=movie&action=index');
            exit;
        }

        if (!CSRFTokenService::validateToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalid.');
        }

        $id = (int)($_POST['id'] ?? 0);
        $hallId = (int)($_POST['hall_id'] ?? 0);
        $startTime = trim($_POST['start_time'] ?? '');
        $price = (float)($_POST['price'] ?? 0);

        if ($id <= 0 || $hallId <= 0 || $startTime === '' || $price <= 0) {
            echo "Toate câmpurile sunt obligatorii.";
            return;
        }

        $pdo = getDB();
        $stmt = $pdo->prepare('
            UPDATE showtimes
            SET hall_id = :hall_id, start_time = :start_time, price = :price
            WHERE id = :id
        ');
        $stmt->execute([
            'hall_id' => $hallId,
            'start_time' => $startTime,
            'price' => $price,
            'id' => $id,
        ]);

        // Obținem movie_id pentru redirect
        $movieStmt = $pdo->prepare('SELECT movie_id FROM showtimes WHERE id = :id');
        $movieStmt->execute(['id' => $id]);
        $movie = $movieStmt->fetch();

        header('Location: /?controller=movie&action=show&id=' . (int)$movie['movie_id']);
        exit;
    }

    public function delete(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        if (!CSRFTokenService::validateToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalid.');
        }

        $id = (int)($_POST['id'] ?? 0);
        $movieId = (int)($_POST['movie_id'] ?? 0);

        if ($id > 0) {
            $pdo = getDB();
            $stmt = $pdo->prepare('DELETE FROM showtimes WHERE id = :id');
            $stmt->execute(['id' => $id]);
        }

        header('Location: /?controller=movie&action=show&id=' . $movieId);
        exit;
    }


    // salvare in DB
    public function store(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=movie&action=index');
            exit;
        }

        if (!CSRFTokenService::validateToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalid.');
        }

        $movieId = (int)($_POST['movie_id'] ?? 0);
        $hallId = (int)($_POST['hall_id'] ?? 0);
        $startTime = trim($_POST['start_time'] ?? '');
        $price = (float)($_POST['price'] ?? 0);

        if ($movieId <= 0 || $hallId <= 0 || $startTime === '' || $price <= 0) {
            echo "Toate câmpurile sunt obligatorii.";
            return;
        }

        $pdo = getDB();
        $stmt = $pdo->prepare('
            INSERT INTO showtimes (movie_id, hall_id, start_time, price)
            VALUES (:movie_id, :hall_id, :start_time, :price)
        ');

        $stmt->execute([
            'movie_id'   => $movieId,
            'hall_id'    => $hallId,
            'start_time' => $startTime,
            'price'      => $price,
        ]);

        header('Location: /?controller=movie&action=show&id=' . $movieId);
        exit;
    }
}
