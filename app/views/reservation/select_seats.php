<?php
// $showtime, $reservedSeats
?>

<h2>Selectează locuri</h2>

<p>
    Film: <strong><?php echo htmlspecialchars($showtime['movie_id']); ?></strong> (o să-l afișăm frumos mai târziu)<br>
    Sală: <strong><?php echo htmlspecialchars($showtime['hall_name']); ?></strong><br>
    Data & ora: <strong><?php echo htmlspecialchars($showtime['start_time']); ?></strong><br>
    Preț: <strong><?php echo htmlspecialchars($showtime['price']); ?> RON</strong>
</p>

<form method="post" action="/?controller=reservation&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">
    <input type="hidden" name="showtime_id" value="<?php echo (int)$showtime['id']; ?>">

    <div class="seats-grid">
        <?php for ($row = 1; $row <= $showtime['total_rows']; $row++): ?>
            <div class="seat-row">
                <?php for ($seat = 1; $seat <= $showtime['seats_per_row']; $seat++):
                    $key = $row . '-' . $seat;
                    $isReserved = !empty($reservedSeats[$key]);
                    if ($isReserved): ?>
                        <div class="seat reserved"><?php echo $seat; ?></div>
                    <?php else: ?>
                        <label class="seat available">
                            <input type="checkbox" name="seats[]" value="<?php echo $key; ?>">
                            <?php echo $seat; ?>
                        </label>
                    <?php endif;
                endfor; ?>
            </div>
        <?php endfor; ?>
    </div>

    <br>
    <button type="submit">Confirmă rezervarea</button>
</form>

<p style="margin-top:12px;">
    <a href="/?controller=movie&action=index">&laquo; Înapoi la filme</a>
</p>
