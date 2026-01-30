<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cubic Market - La Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=home">
            <span style="color: var(--mc-gold);">>_</span> CUBIC MARKET
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php?page=boutique">🛒 Boutique</a></li>

                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item mx-2">
                        <a href="index.php?page=profile" class="nav-link text-white border border-secondary px-3 rounded bg-dark text-decoration-none">
                            <img src="https://mc-heads.net/avatar/<?= e($_SESSION['user']['pseudo']) ?>/24" alt="" class="me-1">
                            <?= e($_SESSION['user']['pseudo']) ?>
                        </a>
                    </li>
                    <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                        <li class="nav-item"><a class="nav-link text-warning" href="index.php?page=admin">⚙️ Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link text-danger" href="index.php?page=logout">Déconnexion</a></li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-sm btn-outline-light ms-2" href="index.php?page=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div style="margin-top: 100px; min-height: 80vh;">
    <div class="container">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show border-0 bg-opacity-75" role="alert"
                 style="background-color: var(--mc-panel); color: white; border-left: 5px solid var(--mc-gold) !important;">
                <strong>Notification:</strong> <?= $_SESSION['flash']['message'] ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?>
    </div>
</div>

<footer class="text-center mt-5 py-4">
    <div class="container">
        <p class="mb-0">&copy; 2026 CUBIC MARKET. NOT AFFILIATED WITH MOJANG AB.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>