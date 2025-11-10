<h2>Înregistrare</h2>

<?php if (!empty($error)): ?>
    <p style="color:#f87171;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" action="/?controller=auth&action=register">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">

    <label>Nume</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Parolă</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirmă parola</label><br>
    <input type="password" name="password_confirm" required><br><br>

    <button type="submit">Creează cont</button>
</form>


<p>Ai deja cont?
    <a href="/?controller=auth&action=loginForm">Login</a>
</p>
