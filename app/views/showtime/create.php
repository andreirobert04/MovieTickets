<h2>Adaugă proiecție nouă</h2>

<p>
    Film: <strong><?php echo htmlspecialchars($movie['title']); ?></strong><br>
    Durată: <?php echo (int)$movie['duration_minutes']; ?> min
    <?php if (!empty($movie['release_year'])): ?>
        • <?php echo (int)$movie['release_year']; ?>
    <?php endif; ?>
</p>

<form method="post" action="/?controller=showtime&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">
    <input type="hidden" name="movie_id" value="<?php echo (int)$movie['id']; ?>">

    <label>Sală</label><br>
    <select name="hall_id" required>
        <option value="">Alege sala</option>
        <?php foreach ($halls as $hall): ?>
            <option value="<?php echo (int)$hall['id']; ?>">
                <?php echo htmlspecialchars($hall['name']); ?>
                (<?php echo (int)$hall['total_rows']; ?> rânduri,
                 <?php echo (int)$hall['seats_per_row']; ?> locuri/rând)
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Data & ora</label><br>
    <input type="datetime-local" name="start_time" required>
    <br><br>

    <label>Preț bilet (RON)</label><br>
    <input type="number" name="price" step="0.5" min="0" required>
    <br><br>

    <button type="submit" class="btn-primary">Salvează proiecția</button>
</form>

<p style="margin-top:10px;">
    <a href="/?controller=movie&action=show&id=<?php echo (int)$movie['id']; ?>">
        &laquo; Înapoi la detalii film
    </a>
</p>
