<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/../services/AuthService.php';

class MovieController extends Controller
{
    public function index(): void
    {
        $movies = Movie::getAll();
        $this->render('movie/index', ['movies' => $movies]);
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $movie = Movie::getById($id);

        if (!$movie) {
            http_response_code(404);
            echo "Filmul nu a fost gasit.";
            return;
        }

        $showtimes = Showtime::getByMovie($id);

        $this->render('movie/show', [
            'movie' => $movie,
            'showtimes' => $showtimes
        ]);
    }

    public function createForm(): void
    {
        $this->requireAdmin();
        $this->render('movie/create');
    }

    public function store(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=movie&action=createForm');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = (int)($_POST['duration_minutes'] ?? 0);
        $genre = trim($_POST['genre'] ?? '');
        $rating = (float)($_POST['rating'] ?? 0);
        $releaseYear = (int)($_POST['release_year'] ?? 0);

        if ($title === '' || $duration <= 0) {
            echo "Titlu și durată obligatorii.";
            return;
        }

        // Upload poster
        $posterName = 'default.jpg';
        if (!empty($_FILES['poster']['name'])) {
            $uploadDir = __DIR__ . '/../../public/images/posters/';
            $posterName = basename($_FILES['poster']['name']);
            $targetPath = $uploadDir . $posterName;

            $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($fileType, $allowed)) {
                echo "Format imagine invalid (acceptate: JPG, PNG).";
                return;
            }

            if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetPath)) {
                // ok
            } else {
                echo "Eroare la încărcarea fișierului.";
                return;
            }
        }

        $pdo = getDB();
        $stmt = $pdo->prepare('
            INSERT INTO movies (title, description, duration_minutes, rating, genre, poster_path, release_year)
            VALUES (:t, :d, :dur, :r, :g, :p, :y)
        ');
        $stmt->execute([
            't' => $title,
            'd' => $description,
            'dur' => $duration,
            'r' => $rating,
            'g' => $genre,
            'p' => $posterName,
            'y' => $releaseYear ?: null
        ]);

        header('Location: /?controller=movie&action=index');
        exit;
    }

    public function editForm(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $movie = Movie::getById($id);

        if (!$movie) {
            echo "Filmul nu există.";
            return;
        }

        $this->render('movie/edit', ['movie' => $movie]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=movie&action=index');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = (int)($_POST['duration_minutes'] ?? 0);
        $genre = trim($_POST['genre'] ?? '');
        $rating = (float)($_POST['rating'] ?? 0);
        $releaseYear = (int)($_POST['release_year'] ?? 0);

        $pdo = getDB();

        // preluam filmul curent
        $stmt = $pdo->prepare('SELECT poster_path FROM movies WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $old = $stmt->fetch();
        $posterName = $old ? $old['poster_path'] : 'default.jpg';

        // verificam daca a fost incarcat un nou fisier
        if (!empty($_FILES['poster']['name'])) {
            $uploadDir = __DIR__ . '/../../public/images/posters/';
            $posterName = basename($_FILES['poster']['name']);
            $targetPath = $uploadDir . $posterName;

            $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($fileType, $allowed)) {
                echo "Format invalid (JPG, PNG).";
                return;
            }

            move_uploaded_file($_FILES['poster']['tmp_name'], $targetPath);
        }

        $stmt = $pdo->prepare('
            UPDATE movies
            SET title = :t, description = :d, duration_minutes = :dur,
                genre = :g, rating = :r, poster_path = :p, release_year = :y
            WHERE id = :id
        ');
        $stmt->execute([
            't' => $title,
            'd' => $description,
            'dur' => $duration,
            'g' => $genre,
            'r' => $rating,
            'p' => $posterName,
            'id' => $id,
            'y' => $releaseYear ?: null
        ]);

        header('Location: /?controller=movie&action=index');
        exit;
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $pdo = getDB();
            $stmt = $pdo->prepare('DELETE FROM movies WHERE id = :id');
            $stmt->execute(['id' => $id]);
        }

        header('Location: /?controller=movie&action=index');
        exit;
    }

    private function requireAdmin(): void
    {
        if (!AuthService::isAdmin()) {
            http_response_code(403);
            echo "Access denied. Admin only.";
            exit;
        }
    }

}
