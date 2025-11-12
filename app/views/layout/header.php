<?php 
    require_once __DIR__ . '/../../services/AuthService.php';
?>


<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>MovieTickets</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/responsive.css">
</head>
<body>
<header>
    <h1>MovieTickets</h1>
    <nav>
        <a href="/?controller=movie&action=index">Filme</a>

        <?php if (AuthService::isAdmin()): ?>
            <a href="/?controller=movie&action=createForm">Adaugă film</a>
        <?php endif; ?>

        <?php if (!empty($_SESSION['user'])): ?>
            <a href="/?controller=reservation&action=myReservations">Rezervările mele</a>

            <span style="margin-left:10px;">
                Salut, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                <?php if (AuthService::isAdmin()): ?>
                    <strong>(admin)</strong>
                <?php endif; ?>
            </span>

            <a href="/?controller=auth&action=logout" style="margin-left:10px;">Logout</a>
        <?php else: ?>
            <a href="/?controller=auth&action=loginForm">Login</a>
            <a href="/?controller=auth&action=registerForm">Register</a>
        <?php endif; ?>

    </nav>

</header>
<main>
