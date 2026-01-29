<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0" style="color: var(--mc-gold); text-shadow: 2px 2px 0 #000; font-family: 'VT323', monospace; font-size: 2.5rem;">
        🛠️ PANEL ADMINISTRATEUR
    </h2>
    <span class="badge bg-dark border border-secondary text-muted">V 1.0.2</span>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card-custom p-4 h-100">
            <h4 class="text-info mb-4" style="font-family: 'VT323'; letter-spacing: 1px;">> AJOUTER UN ITEM</h4>

            <form action="index.php?page=admin_add" method="post" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="gaming-label">Type de produit</label>
                    <select name="category" id="categorySelector" class="form-select input-gaming" onchange="toggleForm()">
                        <option value="Arme">⚔️ Arme</option>
                        <option value="Grade">👑 Grade</option>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="gaming-label">Nom</label>
                        <input type="text" name="name" class="form-control input-gaming" placeholder="Ex: Excalibur" required>
                    </div>
                    <div class="col-4">
                        <label class="gaming-label">Prix (€)</label>
                        <input type="number" step="0.01" name="price" class="form-control input-gaming text-success fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="gaming-label">Visuel</label>
                    <label for="fileUploadAdd" class="image-upload-zone w-100 py-3">
                        <img id="imgPreviewAdd" src="https://cdn-icons-png.flaticon.com/512/126/126477.png" class="preview-image" style="height: 60px; opacity: 0.5;" alt="Aperçu">
                        <span class="d-block text-muted small mt-2">Cliquez pour ajouter une image</span>
                        <input type="file" id="fileUploadAdd" name="image_file" class="d-none" onchange="previewFileAdd()">
                    </label>
                    <input type="text" name="image_url" class="form-control input-gaming form-control-sm mt-2" placeholder="Ou coller une URL..." oninput="document.getElementById('imgPreviewAdd').src = this.value">
                </div>

                <hr class="border-secondary my-4">

                <div id="formArme">
                    <h6 class="text-danger mb-3" style="font-family: 'VT323'; font-size: 1.2rem;">STATS OFFENSIVES</h6>
                    <div class="mb-3">
                        <label class="gaming-label">✨ Enchantements</label>
                        <input type="text" name="weapon_enchants" class="form-control input-gaming" placeholder="Tranchant V, Aura de Feu II">
                    </div>
                    <div class="mb-3">
                        <label class="gaming-label">📜 Lore</label>
                        <textarea name="weapon_lore" class="form-control input-gaming" rows="2" placeholder="L'histoire de l'arme..."></textarea>
                    </div>
                </div>

                <div id="formGrade" style="display: none;">
                    <h6 class="text-warning mb-3" style="font-family: 'VT323'; font-size: 1.2rem;">CONFIGURATION VIP</h6>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="gaming-label">Préfixe</label>
                            <input type="text" name="rank_prefix" class="form-control input-gaming" placeholder="[VIP]">
                        </div>
                        <div class="col-6">
                            <label class="gaming-label">Couleur</label>
                            <div class="d-flex">
                                <input type="color" name="rank_color" class="form-control form-control-color bg-transparent border-0" value="#55FF55" style="width: 40px;" onchange="this.nextElementSibling.value = this.value">
                                <input type="text" value="#55FF55" class="form-control input-gaming ms-2" style="font-size: 0.8rem;" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="gaming-label" style="font-size: 0.8rem;">Coins</label>
                            <input type="number" name="rank_coins" class="form-control input-gaming" value="0">
                        </div>
                        <div class="col-4">
                            <label class="gaming-label" style="font-size: 0.8rem;">Homes</label>
                            <input type="number" name="rank_homes" class="form-control input-gaming" value="2">
                        </div>
                        <div class="col-4">
                            <label class="gaming-label" style="font-size: 0.8rem;">XP</label>
                            <select name="rank_xp" class="form-select input-gaming px-1">
                                <option value="1.0x">1.0x</option>
                                <option value="1.5x">1.5x</option>
                                <option value="2.0x">2.0x</option>
                                <option value="3.0x">3.0x</option>
                            </select>
                        </div>
                    </div>

                    <label class="gaming-label mb-2">OPTIONS ACTIVÉES</label>
                    <div class="row g-2">
                        <?php
                        $options = [
                                'fly' => '🕊️ Fly', 'feed' => '🍖 Feed', 'repair' => '🔨 Repair',
                                'cooldown' => '⚡ No Cool.', 'full_join' => '🚀 Full Join', 'particles' => '✨ Particules'
                        ];
                        ?>
                        <?php foreach($options as $key => $label): ?>
                            <div class="col-6">
                                <div class="switch-container py-1 px-2">
                                    <div class="form-check form-switch m-0 d-flex align-items-center">
                                        <input class="form-check-input gaming-switch" type="checkbox" name="rank_<?= $key ?>" id="add_<?= $key ?>" style="width: 2.5em; height: 1.2em;">
                                        <label class="form-check-label text-white ms-2 small" for="add_<?= $key ?>" style="cursor:pointer;"><?= $label ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-minecraft w-100 py-2 fs-5">
                        AJOUTER AU CATALOGUE
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card-custom p-4 h-100">
            <h4 class="text-info mb-3" style="font-family: 'VT323'; letter-spacing: 1px;">📦 INVENTAIRE SERVEUR</h4>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle" style="border-color: #444;">
                    <thead>
                    <tr class="text-muted small text-uppercase" style="font-family: 'Rajdhani';">
                        <th>Visuel</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <div style="width: 40px; height: 40px; background: rgba(0,0,0,0.3); border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?= e($p->getImage()) ?>" style="max-width: 100%; max-height: 100%; filter: drop-shadow(0 0 2px rgba(255,255,255,0.5));">
                                </div>
                            </td>
                            <td class="fw-bold" style="color: var(--mc-diamond); font-family: 'Rajdhani'; font-size: 1.1rem;"><?= e($p->getName()) ?></td>
                            <td class="text-success fw-bold"><?= e($p->getPrice()) ?> €</td>
                            <td class="text-end">
                                <a href="index.php?page=admin_edit&id=<?= $p->getId() ?>"
                                   class="btn btn-sm btn-outline-info me-1"
                                   title="Modifier">
                                    ✏️
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="openDeleteModal(<?= $p->getId() ?>, '<?= e($p->getName()) ?>')"
                                        title="Supprimer">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #1e1e24; border: 2px solid #FF5555; box-shadow: 0 0 20px rgba(255, 85, 85, 0.3);">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-danger" style="font-family: 'VT323'; font-size: 1.5rem;">⚠️ ZONE DANGEREUSE</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="text-white fs-5">Voulez-vous vraiment détruire cet item ?</p>
                <h2 id="deleteItemName" class="text-warning fw-bold my-3" style="font-family: 'VT323'; letter-spacing: 2px;">NOM_ITEM</h2>
                <p class="text-muted small">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer border-top border-secondary justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">ANNULER</button>
                <form action="index.php?page=admin_delete" method="POST">
                    <input type="hidden" name="id" id="deleteItemId" value="">
                    <button type="submit" class="btn btn-danger fw-bold">SUPPRIMER DÉFINITIVEMENT</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', toggleForm);
</script>