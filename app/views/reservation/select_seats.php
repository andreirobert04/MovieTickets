<?php
// $showtime, $reservedSeats
?>

<h2>Selectează locuri</h2>

<p>
    Film: <strong><?php echo htmlspecialchars($showtime['movie_title'] ?? $showtime['title'] ?? ''); ?></strong><br>
    Sală: <strong><?php echo htmlspecialchars($showtime['hall_name']); ?></strong><br>
    Data & Ora: <strong><?php echo htmlspecialchars($showtime['start_time']); ?></strong><br>
    Preț bilet: <strong><?php echo (float)$showtime['price']; ?> RON</strong>
</p>

<form method="post" action="/?controller=reservation&action=store">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">
    <input type="hidden" name="showtime_id" value="<?php echo (int)$showtime['id']; ?>">

    <div class="seating-area">
        <div class="screen">
            <span>ECRAN</span>
        </div>

        <div class="seats-grid" style="grid-template-columns: repeat(<?php echo (int)$showtime['seats_per_row']; ?>, 32px);">
            <?php
            for ($r = 1; $r <= (int)$showtime['total_rows']; $r++):
                for ($c = 1; $c <= (int)$showtime['seats_per_row']; $c++):
                    $key = "$r-$c";
                    $isReserved =
                        (!empty($reservedSeats[$key])) ||
                        (!empty($reservedSeats[$r]) && !empty($reservedSeats[$r][$c]));
            ?>
                <?php if ($isReserved): ?>
                    <div class="seat reserved" data-seat="<?php echo $key; ?>" title="Ocupat">
                        <?php echo $r . '-' . $c; ?>
                    </div>
                <?php else: ?>
                    <label class="seat available" data-seat="<?php echo $key; ?>" title="Disponibil">
                        <input type="checkbox" name="seats[]" value="<?php echo $key; ?>">
                        <?php echo $r . '-' . $c; ?>
                    </label>
                <?php endif; ?>
            <?php endfor; endfor; ?>
        </div>
    

        <div class="legend">
            <div class="legend-item">
                <span class="seat-box available-box"></span>
                <span>Loc liber</span>
            </div>
            <div class="legend-item">
                <span class="seat-box selected-box"></span>
                <span>Loc selectat</span>
            </div>
            <div class="legend-item">
                <span class="seat-box reserved-box"></span>
                <span>Loc ocupat</span>
            </div>
        </div>

        <button type="submit" class="btn-primary" style="margin-top: 16px;">
            Confirmă rezervarea
        </button>
    </div>
</form>

<p style="margin-top: 10px;">
    <a href="/?controller=movie&action=show&id=<?php echo (int)$showtime['movie_id']; ?>">
        &laquo; Înapoi la detalii film
    </a>
</p>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.seat.available input[type="checkbox"]').forEach(function (input) {
        const seat = input.closest('.seat');

        if (input.checked) {
            seat.classList.add('selected');
        }

        input.addEventListener('change', function () {
            if (input.checked) {
                seat.classList.add('selected');
            } else {
                seat.classList.remove('selected');
            }
        });
    });
});
</script>
