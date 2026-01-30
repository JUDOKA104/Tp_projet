<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card-custom p-5">
            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary">
                <div>
                    <h6 class="text-muted text-uppercase mb-1">Back Office</h6>
                    <h2 class="text-white mb-0" style="text-shadow: 0 0 10px rgba(255,255,255,0.3);">
                        ✏️ ÉDITER <span style="color: var(--mc-gold);"><?= e($product->getName()) ?></span>
                    </h2>
                </div>
                <a href="index.php?page=admin" class="btn btn-outline-light px-4">RETOUR</a>
            </div>

            <form action="index.php?page=admin_edit" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <input type="hidden" name="id" value="<?= $product->getId() ?>">

                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="gaming-label">Nom de l'item</label>
                            <input type="text" name="name" value="<?= e($product->getName()) ?>" class="form-control input-gaming" required>
                        </div>
                        <div class="mb-4">
                            <label class="gaming-label">Prix (€)</label>
                            <input type="number" step="0.01" name="price" value="<?= e($product->getPrice()) ?>" class="form-control input-gaming text-success fw-bold" required>
                        </div>

                        <div class="mb-3">
                            <label class="gaming-label">Visuel</label>
                            <label for="fileUpload" class="image-upload-zone w-100">
                                <img id="imgPreview" src="<?= e($product->getImage()) ?>" class="preview-image" alt="Aperçu">
                                <span class="d-block text-muted small mt-2">Cliquez pour changer l'image</span>
                                <input type="file" id="fileUpload" name="image_file" class="d-none" onchange="previewFile()">
                            </label>

                            <div class="mt-2">
                                <input type="text" name="image_url" class="form-control input-gaming form-control-sm" placeholder="Ou coller une URL..." value="<?= e($product->getImage()) ?>" oninput="document.getElementById('imgPreview').src = this.value">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 ps-md-5 border-start border-secondary">

                        <?php if ($product->getCategory() === 'Arme'): ?>
                            <h4 class="text-danger mb-4" style="font-family: 'VT323';">> STATISTIQUES OFFENSIVES</h4>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="gaming-label">✨ Enchantements</label>
                                    <?php $enchantsStr = !empty($data['enchants']) ? implode(', ', $data['enchants']) : ''; ?>
                                    <input type="text" name="weapon_enchants" value="<?= e($enchantsStr) ?>" class="form-control input-gaming" placeholder="Tranchant V, Feu II...">
                                </div>
                                <div class="col-12">
                                    <label class="gaming-label">📜 Lore (Histoire)</label>
                                    <textarea name="weapon_lore" rows="4" class="form-control input-gaming"><?= e($data['lore'] ?? '') ?></textarea>
                                </div>
                            </div>

                        <?php elseif ($product->getCategory() === 'Grade'): ?>
                            <h4 class="text-warning mb-4" style="font-family: 'VT323';">> CONFIGURATION VIP</h4>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="gaming-label">💬 Préfixe</label>
                                    <input type="text" name="rank_prefix" value="<?= e($data['prefix'] ?? '') ?>" class="form-control input-gaming">
                                </div>
                                <div class="col-md-6">
                                    <label class="gaming-label">🎨 Couleur</label>
                                    <div class="d-flex">
                                        <input type="color" name="rank_color" value="<?= e($data['color'] ?? '#ffffff') ?>" class="form-control form-control-color bg-transparent border-0" style="width: 50px;" onchange="this.nextElementSibling.value = this.value">
                                        <input type="text" value="<?= e($data['color'] ?? '#ffffff') ?>" class="form-control input-gaming ms-2" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-4">
                                    <label class="gaming-label">💰 Coins</label>
                                    <input type="number" name="rank_coins" value="<?= $data['coins'] ?? 0 ?>" class="form-control input-gaming">
                                </div>
                                <div class="col-4">
                                    <label class="gaming-label">🏠 Homes</label>
                                    <input type="number" name="rank_homes" value="<?= $data['homes'] ?? 0 ?>" class="form-control input-gaming">
                                </div>
                                <div class="col-4">
                                    <label class="gaming-label">✨ XP</label>
                                    <select name="rank_xp" class="form-select input-gaming">
                                        <?php $xp = $data['xp'] ?? '1.0x'; ?>
                                        <option value="1.0x" <?= $xp=='1.0x'?'selected':'' ?>>1.0x</option>
                                        <option value="1.5x" <?= $xp=='1.5x'?'selected':'' ?>>1.5x</option>
                                        <option value="2.0x" <?= $xp=='2.0x'?'selected':'' ?>>2.0x</option>
                                        <option value="3.0x" <?= $xp=='3.0x'?'selected':'' ?>>3.0x</option>
                                    </select>
                                </div>
                            </div>

                            <label class="gaming-label mb-3">AVANTAGES ACTIVÉS</label>
                            <div class="row g-3">
                                <?php $checked = fn($key) => !empty($data[$key]) ? 'checked' : ''; ?>

                                <?php
                                $options = [
                                        'fly' => '🕊️ Fly', 'feed' => '🍖 Feed', 'repair' => '🔨 Repair',
                                        'cooldown' => '⚡ No Cool.', 'full_join' => '🚀 Full Join', 'particles' => '✨ Particules'
                                ];
                                ?>
                                <?php foreach($options as $key => $label): ?>
                                    <div class="col-md-6">
                                        <div class="switch-container">
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input gaming-switch" type="checkbox" name="rank_<?= $key ?>" id="sw_<?= $key ?>" <?= $checked($key) ?>>
                                                <label class="form-check-label text-white ms-2" for="sw_<?= $key ?>" style="cursor:pointer;"><?= $label ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="mt-5 pt-3 border-top border-secondary">
                    <button type="submit" class="btn btn-minecraft w-100 py-3 fw-bold fs-4">
                        💾 SAUVEGARDER
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>