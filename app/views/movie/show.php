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

<h3>Proiecții disponibile</h3>

<?php if (!empty($showtimes)): ?>
    <table class="showtimes-table">
        <thead>
            <tr>
                <th>Data & ora</th>
                <th>Sală</th>
                <th>Preț</th>
                <th></th> <!-- coloană pentru buton -->
            </tr>
        </thead>
        <tbody>
        <?php foreach ($showtimes as $st): ?>
            <tr>
                <td><?php echo htmlspecialchars($st['start_time']); ?></td>
                <td><?php echo htmlspecialchars($st['hall_name']); ?></td>
                <td><?php echo number_format($st['price'], 2); ?> RON</td>
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
