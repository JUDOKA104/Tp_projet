<?php
/** @var array $products */

// Préparation des données (inchangé)
$weapons = [];
$ranks = [];

foreach ($products as $product) {
    if ($product->getCategory() === 'Arme') {
        $weapons[] = $product;
    } else {
        $ranks[] = $product;
    }
}
?>

    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold" style="text-shadow: 4px 4px 0px #000;">LA BOUTIQUE</h1>
        <p class="lead text-light">Équipe-toi pour la victoire. Règne sur le serveur.</p>
        <hr class="w-25 mx-auto border-info" style="opacity: 1; height: 3px;">
    </div>

    <div class="category-nav">
        <button class="filter-btn" data-filter="Arme">⚔️ ARMES</button>
        <button class="filter-btn" data-filter="Grade">👑 GRADES</button>
    </div>

    <div id="shop-container">

        <div id="section-Arme" class="product-section mb-5 hidden">
            <h3 class="text-info mb-4 ps-3 border-start border-4 border-info">> ARMURERIE</h3>
            <div class="row g-4">
                <?php if(empty($weapons)): ?>
                    <p class="text-muted text-center">Aucune arme disponible.</p>
                <?php else: ?>
                    <?php foreach ($weapons as $product): ?>
                        <?= renderWeaponCard($product) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="section-Grade" class="product-section mb-5 hidden">
            <h3 class="text-warning mb-4 ps-3 border-start border-4 border-warning">> GRADES VIP</h3>

            <?php
            // Configuration du tableau (inchangé)
            $featuresList = [
                    ['key' => 'prefix',     'label' => '💬 Préfixe Chat'],
                    ['key' => 'homes',      'label' => '🏠 Résidences'],
                    ['key' => 'xp',         'label' => '✨ Multiplicateur XP'],
                    ['key' => 'coins',      'label' => '💰 Coins Mensuels'],
                    ['key' => 'fly',        'label' => '🕊️ Fly Lobby'],
                    ['key' => 'feed',       'label' => '🍖 /feed'],
                    ['key' => 'repair',     'label' => '🔨 /repair'],
                    ['key' => 'full_join',  'label' => '🚀 Join Full'],
            ];
            usort($ranks, fn($a, $b) => $a->getPrice() <=> $b->getPrice());
            ?>

            <div class="comparison-table-wrapper">
                <table class="comparison-table">
                    <thead>
                    <tr>
                        <th class="th-feature">FONCTIONNALITÉS</th>
                        <?php foreach ($ranks as $rank):
                            $data = json_decode($rank->getDescription() ?? '', true) ?? [];
                            $color = $data['color'] ?? '#fff';
                            $displayName = str_replace('VIP ', '', $rank->getName());
                            ?>
                            <th>
                            <span style="font-family: 'VT323'; font-size: 2rem; color: <?= $color ?>; text-shadow: 2px 2px 0 #000;">
                                <?= e($displayName) ?>
                            </span>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($featuresList as $feature): ?>
                        <tr>
                            <td class="text-start ps-4 fw-bold text-light"><?= $feature['label'] ?></td>
                            <?php foreach ($ranks as $rank):
                                $data = json_decode($rank->getDescription() ?? '', true) ?? [];
                                $val = $data[$feature['key']] ?? null;
                                ?>
                                <td>
                                    <?php if ($val === true): ?> <span class="check-icon">✔</span>
                                    <?php elseif ($val === false): ?> <span class="cross-icon">✖</span>
                                    <?php elseif ($val === null): ?> <span class="text-muted">-</span>
                                    <?php else: ?>
                                        <span class="text-white"><?= e((string)$val) ?></span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="table-footer-row">
                        <td></td>
                        <?php foreach ($ranks as $rank): ?>
                            <td>
                                <span class="rank-price-display"><?= e($rank->getPrice()) ?> €</span>
                                <button class="btn btn-minecraft w-100 btn-sm"
                                        onclick='openPaymentModal(<?= $rank->getId() ?>, <?= htmlspecialchars(json_encode($rank->getName()), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode((string)$rank->getPrice()), ENT_QUOTES) ?>)'>
                                    ACHETER
                                </button>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #1e1e24; border: 2px solid var(--mc-gold); box-shadow: 0 0 20px rgba(255, 170, 0, 0.2);">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title text-white" style="font-family: 'VT323'; font-size: 1.8rem;">💳 CAISSE</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="index.php?page=buy" method="POST" id="paymentForm">
                        <input type="hidden" name="product_id" id="modalProductId">

                        <div class="text-center mb-4 p-3" style="background: rgba(0,0,0,0.2); border-radius: 8px;">
                            <h4 id="modalProductName" class="text-info mb-1" style="font-family: 'VT323'; font-size: 2rem;">ITEM</h4>
                            <h2 id="modalProductPrice" class="text-success fw-bold m-0">0.00 €</h2>
                        </div>

                        <div class="mb-4">
                            <label class="gaming-label">Moyen de paiement</label>
                            <select name="payment_method" class="form-select input-gaming" id="methodSelector" onchange="togglePaymentFields()">
                                <option value="card">💳 Carte Bancaire (Stripe Sim)</option>
                                <option value="paypal">🅿️ PayPal</option>
                            </select>
                        </div>

                        <div id="cardFields">
                            <div class="mb-3">
                                <label class="gaming-label">Numéro de Carte</label>
                                <input type="text" name="card_number" class="form-control input-gaming" placeholder="0000 0000 0000 0000" maxlength="16">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="gaming-label">Expiration</label>
                                    <input type="text" name="expiry" class="form-control input-gaming" placeholder="MM/YY">
                                </div>
                                <div class="col-6">
                                    <label class="gaming-label">CVV</label>
                                    <input type="text" name="cvv" class="form-control input-gaming" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>

                        <div id="paypalFields" style="display: none;">
                            <div class="mb-3">
                                <label class="gaming-label">Email PayPal</label>
                                <input type="email" name="paypal_email" class="form-control input-gaming" placeholder="vous@exemple.com">
                            </div>
                            <div class="alert alert-info bg-dark border border-info text-info d-flex align-items-center">
                                <span class="me-2">ℹ️</span>
                                <small>Vous serez redirigé vers PayPal pour valider la transaction sécurisée.</small>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-top border-secondary">
                            <button type="submit" class="btn btn-minecraft w-100 py-2">
                                CONFIRMER LE PAIEMENT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
/**
 * Fonction Helper pour les Armes (MODIFIÉE pour le Modal)
 */
function renderWeaponCard($product) {
    $img = $product->getImage() ?: 'https://cdn-icons-png.flaticon.com/512/1165/1165187.png';
    $data = json_decode($product->getDescription() ?? '', true);
    if (!is_array($data)) {
        $data = ['damage' => '?', 'lore' => $product->getDescription(), 'enchants' => []];
    }

    $damage = $data['damage'] ?? '?';
    $lore = $data['lore'] ?? '';
    $enchants = $data['enchants'] ?? [];

    ob_start();
    ?>
    <div class="col-md-4">
        <div class="card-custom h-100">
            <div class="card-img-wrapper">
                <img src="<?= e($img) ?>" class="card-img-top" alt="<?= e($product->getName()) ?>">
            </div>

            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-center" style="color: #FF5555; text-shadow: 2px 2px 0 #000;">
                    <?= e($product->getName()) ?>
                </h5>

                <div class="weapon-stats flex-grow-1">
                    <?php if (!empty($enchants)): ?>
                        <ul class="enchant-list">
                            <?php foreach ($enchants as $enchant): ?>
                                <li class="enchant-item">✨ <?= e($enchant) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if (!empty($lore)): ?>
                        <div class="weapon-lore">"<?= e($lore) ?>"</div>
                    <?php endif; ?>
                </div>

                <div class="mt-auto pt-3 border-top border-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase small text-muted">Prix</span>
                        <span class="price-tag"><?= e($product->getPrice()) ?> €</span>
                    </div>
                    <button class="btn btn-minecraft w-100"
                            onclick='openPaymentModal(<?= $product->getId() ?>, <?= htmlspecialchars(json_encode($product->getName()), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode((string)$product->getPrice()), ENT_QUOTES) ?>)'>
                        ACHETER
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>