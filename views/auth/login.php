<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card-custom p-5">
            <div class="text-center mb-4">
                <h2 class="text-white">🔐 CONNEXION</h2>
                <p class="text-muted small">Bon retour parmi nous, aventurier.</p>
            </div>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-danger bg-danger text-white border-0 bg-opacity-25">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?page=login">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="mb-3">
                    <label class="form-label text-uppercase small text-muted">Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary p-3" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-uppercase small text-muted">Mot de passe</label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary p-3" required>
                </div>
                <button type="submit" class="btn btn-minecraft w-100 py-2">SE CONNECTER</button>
            </form>

            <div class="text-center mt-3">
                <a href="index.php?page=register" class="text-decoration-none text-info small">Pas encore de compte ? Créer un compte</a>
            </div>
        </div>
    </div>
</div>