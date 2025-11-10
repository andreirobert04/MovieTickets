<h2>Filme disponibile</h2>

<style>

</style>


<?php if (!empty($movies)): ?>

    <!-- Carousel -->
    <div class="carousel">
        <div class="carousel-track">
            <?php
            // Poți alege între random sau primele 5 filme
            // $featured = array_slice($movies, 0, 5);
            $featured = $movies;

            foreach ($featured as $f):
                $poster = !empty($f['poster_path'])
                    ? "/images/posters/" . htmlspecialchars($f['poster_path'])
                    : "/images/posters/default.jpg";
            ?>
                <div class="carousel-slide">
                    <img src="<?php echo $poster; ?>"
                         alt="<?php echo htmlspecialchars($f['title']); ?>">
                    <h3><?php echo htmlspecialchars($f['title']); ?></h3>
                    <p><?php echo htmlspecialchars($f['genre'] ?? ''); ?></p>
                    <a href="/?controller=movie&action=show&id=<?php echo (int)$f['id']; ?>">
                        Detalii & proiecții
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-btn prev">&#10094;</button>
        <button class="carousel-btn next">&#10095;</button>
    </div>

    <!-- Grid de filme -->
    <div class="movies-grid">
        <?php foreach ($movies as $movie): 
            $poster = !empty($movie['poster_path'])
                ? "/images/posters/" . htmlspecialchars($movie['poster_path'])
                : "/images/posters/default.jpg";
        ?>
            <div class="movie-card">
                <img src="<?php echo $poster; ?>"
                     alt="<?php echo htmlspecialchars($movie['title']); ?>">

                <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                <p>
                    <?php echo htmlspecialchars($movie['genre']); ?>
                    • <?php echo (int)$movie['duration_minutes']; ?> min
                </p>

                <a href="/?controller=movie&action=show&id=<?php echo (int)$movie['id']; ?>">
                    Detalii & proiecții
                </a>
                <br>
                <a href="/?controller=movie&action=editForm&id=<?php echo (int)$movie['id']; ?>">
                    Editează
                </a>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <p>Momentan nu există filme în baza de date.</p>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const btnNext = document.querySelector('.carousel-btn.next');
    const btnPrev = document.querySelector('.carousel-btn.prev');

    if (slides.length === 0) return;

    let index = 0;

    const update = () => {
        // dacă e doar un film, nu mișcăm nimic
        if (slides.length === 1) {
            slides[0].style.transform = 'translateX(0)';
            return;
        }

        const offset = -index * 100;
        track.style.transform = `translateX(${offset}%)`;
    };

    btnNext?.addEventListener('click', () => {
        index = (index + 1) % slides.length;
        update();
    });

    btnPrev?.addEventListener('click', () => {
        index = (index - 1 + slides.length) % slides.length;
        update();
    });

    update();
});
</script>


