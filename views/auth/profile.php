<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card-custom p-4 text-center">
            <div class="mb-3">
                <img src="https://mc-heads.net/body/<?= e($user['pseudo']) ?>/150" alt="Skin">
            </div>
            <h2 class="text-white mb-0"><?= e($user['pseudo']) ?></h2>

            <?php if($user['role'] === 'ADMIN'): ?>
                <span class="badge bg-danger border border-light mt-2">ADMINISTRATEUR</span>
            <?php else: ?>
                <span class="badge bg-success border border-light mt-2">JOUEUR</span>
            <?php endif; ?>

            <hr class="border-secondary my-4">

            <div class="text-start px-3">
                <p class="text-muted mb-1"><small>EMAIL</small></p>
                <p class="text-white"><?= e($_SESSION['user']['pseudo']) // Note: email souvent masqué, ou à récupérer via find() si non stocké en session ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h3 class="text-warning mb-4" style="font-family: 'VT323'; font-size: 2rem;">
            <?= $user['role'] === 'ADMIN' ? '⚡ INVENTAIRE GOD MODE' : '🎒 MON SAC À DOS' ?>
        </h3>

        <?php if (empty($products)): ?>
            <div class="alert alert-dark border-secondary text-center py-5">
                <h4>Votre inventaire est vide...</h4>
                <p class="text-muted">Allez faire un tour en boutique !</p>
                <a href="index.php?page=boutique" class="btn btn-minecraft mt-3 w-auto px-4">ALLER À LA BOUTIQUE</a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($products as $p): ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card-custom p-3 h-100 d-flex flex-column align-items-center">
                            <div style="height: 100px; width: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.2); border-radius: 5px;" class="mb-3">
                                <img src="<?= e($p->getImage()) ?>" style="max-height: 80px; filter: drop-shadow(0 0 5px white);">
                            </div>
                            <h5 class="text-white text-center mb-1" style="font-size: 1.1rem;"><?= e($p->getName()) ?></h5>
                            <small class="text-muted"><?= $p->getCategory() ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>