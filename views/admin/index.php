<h2 class="text-warning">🛠️ Administration du Cubic Market</h2>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h4>Ajouter un item</h4>
            <form action="index.php?page=admin_add" method="post">
                <div class="mb-2">
                    <label>Type de produit</label>
                    <select name="category" class="form-select">
                        <option value="Arme">⚔️ Arme</option>
                        <option value="Grade">👑 Grade</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Nom</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Prix (€)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>URL Image</label>
                    <input type="text" name="image" class="form-control" placeholder="http://...">
                </div>
                <button type="submit" class="btn btn-success w-100">Ajouter au catalogue</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card p-3 shadow-sm">
            <h4>📦 Gestion des stocks</h4>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>#<?= $p->getId() ?></td>
                        <td><?= $p->getName() ?></td>
                        <td><?= $p->getDisplayType() ?></td> <td><?= $p->getPrice() ?> €</td>
                        <td>
                            <a href="index.php?page=admin_delete&id=<?= $p->getId() ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Êtes-vous sûr ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>