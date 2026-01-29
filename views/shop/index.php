<h1>🛒 La Boutique Officielle</h1>
<p>Achetez vos grades et équipements pour dominer le serveur !</p>

<div class="row">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <?php $img = $product->getImage() ?? 'https://via.placeholder.com/300x200?text=No+Image'; ?>
                <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($product->getName()) ?>">

                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($product->getName()) ?>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        <?= $product->getDisplayType() ?> </h6>
                    <p class="card-text">
                        <?= htmlspecialchars($product->getDescription()) ?>
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span class="fs-5 fw-bold text-primary"><?= $product->getPrice() ?> €</span>
                    <button class="btn btn-sm btn-outline-primary">Acheter</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>