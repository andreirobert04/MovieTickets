<h2>Editează proiecția</h2>

<p>
    Film: <strong><?php echo htmlspecialchars($showtime['movie_title']); ?></strong>
</p>

<form method="post" action="/?controller=showtime&action=update">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">
    <input type="hidden" name="id" value="<?php echo (int)$showtime['id']; ?>">

    <label>Sală</label><br>
    <select name="hall_id" required>
        <?php foreach ($halls as $hall): ?>
            <option value="<?php echo (int)$hall['id']; ?>"
                <?php if ($hall['id'] == $showtime['hall_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($hall['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Data & ora</label><br>
    <input type="datetime-local" name="start_time"
           value="<?php echo date('Y-m-d\TH:i', strtotime($showtime['start_time'])); ?>"
           required><br><br>

    <label>Preț bilet (RON)</label><br>
    <input type="number" name="price"
           step="0.5"
           min="0"
           value="<?php echo htmlspecialchars($showtime['price']); ?>"
           required><br><br>

    <button type="submit" class="btn-primary">Salvează modificările</button>
</form>

<p style="margin-top:10px;">
    <a href="/?controller=movie&action=show&id=<?php echo (int)$showtime['movie_id']; ?>">
        &laquo; Înapoi la detalii film
    </a>
</p>
