<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Exceptions/SeatAlreadyReservedException.php';
require_once __DIR__ . '/../models/Exceptions/UnauthorizedActionException.php';


class ReservationController extends Controller
{
    private function requireLogin(): void
    {
        if (empty($_SESSION['user'])) {
            throw new UnauthorizedActionException('Trebuie să fii autentificat pentru a accesa această pagină.');
        }
    }


    public function selectSeats(): void
    {
        try {
            $this->requireLogin();
        } catch (UnauthorizedActionException $e) {
            header('Location: /?controller=auth&action=loginForm');
            exit;
        }

        $showtimeId = isset($_GET['showtime_id']) ? (int)$_GET['showtime_id'] : 0;
        $showtime = Showtime::getById($showtimeId);

        if (!$showtime) {
            http_response_code(404);
            echo "Proiecția nu a fost găsită.";
            return;
        }

        $pdo = getDB();
        $stmt = $pdo->prepare('
            SELECT seat_row, seat_number 
            FROM reservation_seats 
            WHERE showtime_id = :sid
        ');
        $stmt->execute(['sid' => $showtimeId]);
        $reservedSeats = [];
        foreach ($stmt->fetchAll() as $row) {
            $reservedSeats[$row['seat_row'] . '-' . $row['seat_number']] = true;
        }

        $this->render('reservation/select_seats', [
            'showtime' => $showtime,
            'reservedSeats' => $reservedSeats
        ]);
    }

    public function store(): void
    {
        try {
            $this->requireLogin();
        } catch (UnauthorizedActionException $e) {
            header('Location: /?controller=auth&action=loginForm');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?controller=movie&action=index');
            exit;
        }

        if (!CSRFTokenService::validateToken($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Token CSRF invalid.';
            return;
        }

        $showtimeId = (int)($_POST['showtime_id'] ?? 0);
        $seats = $_POST['seats'] ?? [];

        if ($showtimeId <= 0 || empty($seats)) {
            echo "Selectează cel puțin un loc.";
            return;
        }

        $userId = $_SESSION['user']['id'];
        $pdo = getDB();

        try {
            $pdo->beginTransaction();

            // Verificăm să nu fie locuri deja rezervate
            foreach ($seats as $seat) {
                [$r, $c] = array_map('intval', explode('-', $seat));
                $check = $pdo->prepare('
                    SELECT COUNT(*) FROM reservation_seats
                    WHERE showtime_id = :sid AND seat_row = :r AND seat_number = :c
                ');
                $check->execute([
                    'sid' => $showtimeId,
                    'r'   => $r,
                    'c'   => $c
                ]);
                if ($check->fetchColumn() > 0) {
                    throw new SeatAlreadyReservedException("Locul $r-$c este deja rezervat.");
                }

            }

            // Inserăm rezervarea
            $stmt = $pdo->prepare('
                INSERT INTO reservations (user_id, showtime_id)
                VALUES (:uid, :sid)
            ');
            $stmt->execute([
                'uid' => $userId,
                'sid' => $showtimeId
            ]);
            $reservationId = $pdo->lastInsertId();

            // Inserăm locurile
            $insert = $pdo->prepare('
                INSERT INTO reservation_seats (reservation_id, showtime_id, seat_row, seat_number)
                VALUES (:rid, :sid, :r, :c)
            ');

            foreach ($seats as $seat) {
                [$r, $c] = array_map('intval', explode('-', $seat));
                $insert->execute([
                    'rid' => $reservationId,
                    'sid' => $showtimeId,
                    'r'   => $r,
                    'c'   => $c
                ]);
            }

            $pdo->commit();

            header('Location: /?controller=reservation&action=myReservations');
            exit;
        } catch (SeatAlreadyReservedException $e) {
            $pdo->rollBack();
            echo "Eroare la rezervare: " . htmlspecialchars($e->getMessage());
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "A apărut o eroare neașteptată. Încearcă din nou.";
        }

    }

    public function myReservations(): void
    {
        $this->requireLogin();

        $userId = $_SESSION['user']['id'];
        $pdo = getDB();

        $stmt = $pdo->prepare('
            SELECT r.id, r.created_at,
                   s.start_time,
                   m.title
            FROM reservations r
            JOIN showtimes s ON r.showtime_id = s.id
            JOIN movies m ON s.movie_id = m.id
            WHERE r.user_id = :uid
            ORDER BY r.created_at DESC
        ');
        $stmt->execute(['uid' => $userId]);
        $reservations = $stmt->fetchAll();

        $this->render('reservation/my_reservations', [
            'reservations' => $reservations
        ]);
    }
}
