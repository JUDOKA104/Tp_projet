<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card-custom p-5">
            <div class="text-center mb-4">
                <h2 class="text-white">📝 INSCRIPTION</h2>
                <p class="text-muted small">Rejoins la légende.</p>
            </div>

            <form method="post" action="index.php?page=register">
                <div class="mb-3">
                    <label class="form-label text-uppercase small text-muted">Pseudo Minecraft</label>
                    <input type="text" name="pseudo" class="form-control bg-dark text-white border-secondary p-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-uppercase small text-muted">Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary p-3" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-uppercase small text-muted">Mot de passe</label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary p-3" required>
                </div>
                <button type="submit" class="btn btn-minecraft w-100 py-2">CRÉER MON COMPTE</button>
            </form>

            <div class="text-center mt-3">
                <a href="index.php?page=login" class="text-decoration-none text-info small">Déjà un compte ? Se connecter</a>
            </div>
        </div>
    </div>
</div>