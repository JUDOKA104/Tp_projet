<?php
// Préparation des données
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
            // Liste des fonctionnalités (Lignes du tableau)
            $featuresList = [
                    ['key' => 'prefix',     'label' => '💬 Préfixe Chat'],
                    ['key' => 'homes',      'label' => '🏠 Résidences (Sethome)'],
                    ['key' => 'xp',         'label' => '✨ Multiplicateur d\'XP'],
                    ['key' => 'coins',      'label' => '💰 Coins Mensuels'],
                    ['key' => 'fly',        'label' => '🕊️ Fly dans le Lobby'],
                    ['key' => 'feed',       'label' => '🍖 Commande /feed'],
                    ['key' => 'repair',     'label' => '🔨 Commande /repair'],
                    ['key' => 'full_join',  'label' => '🚀 Join Serveur Plein'],
                    ['key' => 'cooldown',   'label' => '⚡ No Chat Cooldown'],
                    ['key' => 'particles',  'label' => '🔥 Particules de Marche'],
                    ['key' => 'slots',      'label' => '👕 Slots Garde-robe'],
            ];

            // Tri par prix (du moins cher au plus cher)
            usort($ranks, fn($a, $b) => $a->getPrice() <=> $b->getPrice());
            ?>

            <div class="comparison-table-wrapper">
                <table class="comparison-table">
                    <thead>
                    <tr>
                        <th class="th-feature">FONCTIONNALITÉS</th>
                        <?php foreach ($ranks as $rank):
                            $data = json_decode($rank->getDescription() ?? '', true);
                            if (!is_array($data)) $data = [];
                            $color = $data['color'] ?? '#fff';

                            // Astuce : On retire "VIP " juste pour l'affichage visuel du tableau
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
                                $data = json_decode($rank->getDescription() ?? '', true);
                                if (!is_array($data)) $data = [];

                                // On récupère la couleur pour pouvoir l'utiliser sur le préfixe
                                $rankColor = $data['color'] ?? '#fff';
                                $val = $data[$feature['key']] ?? null;
                                ?>
                                <td>
                                    <?php if ($val === true): ?>
                                        <span class="check-icon">✔</span>
                                    <?php elseif ($val === false): ?>
                                        <span class="cross-icon">✖</span>
                                    <?php elseif ($val === null): ?>
                                        <span class="text-muted" style="font-size: 1.5rem;">-</span>
                                    <?php else: ?>
                                        <?php
                                        $style = "color: #fff;"; // Blanc par défaut

                                        // Si c'est le préfixe, on applique la couleur du grade + police Minecraft
                                        if ($feature['key'] === 'prefix') {
                                            $style = "color: $rankColor; font-family: 'VT323'; font-size: 1.3rem; text-shadow: 1px 1px 0 #000;";
                                        }
                                        ?>
                                        <span style="<?= $style ?>"><?= e((string)$val) ?></span>
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
                                <button class="btn btn-minecraft w-100 btn-sm">ACHETER</button>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
// Fonction Helper pour les Armes
function renderWeaponCard($product) {
    $img = $product->getImage() ?: 'https://cdn-icons-png.flaticon.com/512/1165/1165187.png';

    // Décodage du JSON
    $data = json_decode($product->getDescription() ?? '', true);

    // Si ce n'est pas du JSON (anciennes armes), on gère le cas simple
    if (!is_array($data)) {
        $data = [
                'damage' => '?',
                'lore' => $product->getDescription(), // On prend le texte brut
                'enchants' => []
        ];
    }

    $damage = $data['damage'] ?? '?';
    $lore = $data['lore'] ?? '';
    $enchants = $data['enchants'] ?? [];

    ob_start();
    ?>
    <div class="col-md-4">
        <div class="card-custom h-100">
            <div class="card-img-wrapper">
                <img src="<?= e($img) ?>" class="card-img-top" alt="<?= e($product->getName()) ?>"
                     style="filter: drop-shadow(0 0 10px rgba(255, 85, 85, 0.4));"> </div>

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
                        <div class="weapon-lore">
                            "<?= e($lore) ?>"
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-auto pt-3 border-top border-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase small text-muted">Prix</span>
                        <span class="price-tag"><?= e($product->getPrice()) ?> €</span>
                    </div>
                    <button class="btn btn-minecraft w-100">ACHETER</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>