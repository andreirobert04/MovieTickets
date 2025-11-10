<h2>Editează filmul</h2>

<form method="post" enctype="multipart/form-data" action="/?controller=movie&action=update">
    <input type="hidden" name="id" value="<?php echo (int)$movie['id']; ?>">

    <label>Titlu</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required><br><br>

    <label>Descriere</label><br>
    <textarea name="description" rows="4" cols="40"><?php echo htmlspecialchars($movie['description']); ?></textarea><br><br>

    <label>Durată (minute)</label><br>
    <input type="number" name="duration_minutes" value="<?php echo (int)$movie['duration_minutes']; ?>" required><br><br>

    <label>Gen</label><br>
    <input type="text" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>"><br><br>

    <label>An lansare</label><br>
    <input type="number" name="release_year"
        value="<?php echo (int)$movie['release_year']; ?>"
        min="1900" max="2100"><br><br>

    <label>Rating (0–10)</label><br>
    <input type="number" step="0.1" name="rating" value="<?php echo (float)$movie['rating']; ?>"><br><br>

    <label>Poster curent</label><br>
    <?php if (!empty($movie['poster_path'])): ?>
        <img src="/images/posters/<?php echo htmlspecialchars($movie['poster_path']); ?>" style="max-width:180px;"><br><br>
    <?php endif; ?>

    <label>Schimbă posterul</label><br>
    <input type="file" name="poster" accept="image/*"><br><br>

    <button type="submit">Salvează modificările</button>
</form>

<p><a href="/?controller=movie&action=index">&laquo; Înapoi la lista de filme</a></p>
