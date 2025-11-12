<?php
// $movie, $showtimes vin din controller
?>

<div class="movie-details">
    <div class="movie-poster">
        <?php if (!empty($movie['poster_path'])): ?>
            <img src="/images/posters/<?php echo htmlspecialchars($movie['poster_path']); ?>"
                 alt="<?php echo htmlspecialchars($movie['title']); ?>">
        <?php else: ?>
            <div class="poster-placeholder">Poster</div>
        <?php endif; ?>
    </div>

    <div class="movie-info">
        <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
        <p><strong>Gen:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
        <p><strong>Durata:</strong> <?php echo (int)$movie['duration_minutes']; ?> min</p>
        <?php if (!empty($movie['rating'])): ?>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($movie['rating']); ?>/10</p>
        <?php endif; ?>
        <?php if (!empty($movie['release_year'])): ?>
            <p><strong>An lansare:</strong> <?php echo (int)$movie['release_year']; ?></p>
        <?php endif; ?>
        <?php if (!empty($movie['description'])): ?>
            <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php if (AuthService::isAdmin()): ?>
    <p>
        <a href="/?controller=showtime&action=createForm&movie_id=<?php echo (int)$movie['id']; ?>"
           class="btn-primary">
            + Adaugă proiecție
        </a>
    </p>
<?php endif; ?>


<h3>Proiecții disponibile</h3>

<?php if (!empty($showtimes)): ?>
    <table class="showtimes-table">
        <thead>
            <tr>
                <th>Data & ora</th>
                <th>Sală</th>
                <th>Preț</th>
                <?php if (AuthService::isAdmin()): ?>
                    <th>Admin</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($showtimes as $st): ?>
            <tr>
                <td><?php echo htmlspecialchars($st['start_time']); ?></td>
                <td><?php echo htmlspecialchars($st['hall_name']); ?></td>
                <td><?php echo number_format($st['price'], 2); ?> RON</td>
                <?php if (AuthService::isAdmin()): ?>
                    <td>
                        <a href="/?controller=showtime&action=editForm&id=<?php echo (int)$st['id']; ?>">Editează</a>
                        |
                        <form method="post"
                              action="/?controller=showtime&action=delete"
                              style="display:inline;"
                              onsubmit="return confirm('Sigur vrei să ștergi această proiecție?');">
                            <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">
                            <input type="hidden" name="id" value="<?php echo (int)$st['id']; ?>">
                            <input type="hidden" name="movie_id" value="<?php echo (int)$movie['id']; ?>">
                            <button type="submit" class="btn-link-danger">Șterge</button>
                        </form>
                    </td>
                <?php endif; ?>
                <td class="actions">
                    <a href="/?controller=reservation&action=selectSeats&showtime_id=<?php echo (int)$st['id']; ?>">
                        Rezervă bilete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nu există proiecții disponibile pentru acest film.</p>
<?php endif; ?>

<p>
    <a href="/?controller=movie&action=index">&laquo; Înapoi la lista de filme</a>
</p>
