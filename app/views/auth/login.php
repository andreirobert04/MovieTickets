<h2>Login</h2>

<?php if (!empty($error)): ?>
    <p style="color:#f87171;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post" action="/?controller=auth&action=login">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFTokenService::generateToken(); ?>">

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Parolă</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Autentificare</button>
</form>


<p>Nu ai cont?
    <a href="/?controller=auth&action=registerForm">Înregistrează-te</a>
</p>
