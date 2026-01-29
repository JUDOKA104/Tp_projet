<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cubic Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=home">🟦 Cubic Market</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?page=boutique">Boutique</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">👤 <?= $_SESSION['user']['pseudo'] ?></span>
                    </li>
                    <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                        <li class="nav-item"><a class="nav-link text-warning" href="index.php?page=admin">Administration</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link text-danger" href="index.php?page=logout">Déconnexion</a></li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?= $content ?>
</div>

<footer class="text-center mt-5 py-3 text-muted">
    &copy; 2026 Cubic Market - Projet PHP POO
</footer>
</body>
</html>