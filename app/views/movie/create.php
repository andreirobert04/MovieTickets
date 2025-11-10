<h2>Adaugă film nou</h2>

<form method="post" enctype="multipart/form-data" action="/?controller=movie&action=store">
    <label>Titlu</label><br>
    <input type="text" name="title" required><br><br>

    <label>Descriere</label><br>
    <textarea name="description" rows="4" cols="40"></textarea><br><br>

    <label>Durată (minute)</label><br>
    <input type="number" name="duration_minutes" required><br><br>

    <label>Gen</label><br>
    <input type="text" name="genre"><br><br>

    <label>An lansare</label><br>
    <input type="number" name="release_year" min="1900" max="2100"><br><br>

    <label>Rating (0–10)</label><br>
    <input type="number" step="0.1" name="rating"><br><br>

    <label>Poster</label><br>
    <input type="file" name="poster" accept="image/*"><br><br>

    <button type="submit">Adaugă film</button>
</form>

<p><a href="/?controller=movie&action=index">&laquo; Înapoi la lista de filme</a></p>
