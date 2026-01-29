<?php
namespace App\Controller;

use App\Model\ProductManager;
use App\Entity\Weapon;
use App\Entity\Rank;

class AdminController extends AbstractController
{
    private function ensureAdmin(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            $this->redirect('index.php?page=login');
        }
    }

    public function index(): void
    {
        $this->ensureAdmin();
        $manager = new ProductManager();
        $this->render('admin/index', ['products' => $manager->findAll()]);
    }

    public function add(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $price = (float) $_POST['price'];
            $category = $_POST['category'];

            // --- GESTION DE L'IMAGE (UPLOAD) ---
            $imagePath = ''; // Image par défaut ou vide

            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['image_file']['name'];
                $filetype = $_FILES['image_file']['type'];
                $filesize = $_FILES['image_file']['size'];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                if (in_array(strtolower($extension), $allowed)) {
                    // On crée un nom unique pour éviter d'écraser les fichiers
                    $newFilename = uniqid() . '.' . $extension;
                    $destination = __DIR__ . '/../../public/img/uploads/' . $newFilename;

                    // Création du dossier s'il n'existe pas
                    if (!is_dir(__DIR__ . '/../../public/img/uploads/')) {
                        mkdir(__DIR__ . '/../../public/img/uploads/', 0777, true);
                    }

                    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
                        $imagePath = 'img/uploads/' . $newFilename;
                    }
                }
            } else {
                // Si pas d'upload, on prend l'URL manuelle si remplie
                $imagePath = $_POST['image_url'] ?? '';
            }

            // --- CONSTRUCTION DU JSON (DESCRIPTION) ---
            $descriptionData = [];

            if ($category === 'Arme') {
                $product = new Weapon($name, $price);
                // On construit le tableau pour les armes
                $descriptionData = [
                    'damage' => $_POST['weapon_damage'] ?? '0',
                    'lore'   => $_POST['weapon_lore'] ?? '',
                    // On transforme "Tranchant V, Aura de Feu" en tableau
                    'enchants' => !empty($_POST['weapon_enchants'])
                        ? array_map('trim', explode(',', $_POST['weapon_enchants']))
                        : []
                ];
            } elseif ($category === 'Grade') {
                $product = new Rank($name, $price);
                // On construit le tableau pour les grades
                $descriptionData = [
                    'prefix'    => $_POST['rank_prefix'] ?? '',
                    'color'     => $_POST['rank_color'] ?? '#ffffff',
                    'coins'     => (int)($_POST['rank_coins'] ?? 0),
                    'homes'     => (int)($_POST['rank_homes'] ?? 0),
                    'slots'     => (int)($_POST['rank_slots'] ?? 0),
                    'xp'        => $_POST['rank_xp'] ?? '1.0x',
                    'fly'       => isset($_POST['rank_fly']),      // Checkbox cochée = true
                    'feed'      => isset($_POST['rank_feed']),
                    'repair'    => isset($_POST['rank_repair']),
                    'full_join' => isset($_POST['rank_full_join']),
                    'cooldown'  => isset($_POST['rank_cooldown']),
                    'particles' => isset($_POST['rank_particles'])
                ];
            } else {
                $this->redirect('index.php?page=admin');
                return;
            }

            // Encodage final en JSON pour la BDD
            $product->setDescription(json_encode($descriptionData));
            $product->setImage($imagePath);

            $manager = new ProductManager();
            $manager->add($product);
        }

        $this->redirect('index.php?page=admin');
    }

    public function edit(): void
    {
        $this->ensureAdmin();
        $manager = new ProductManager();

        // SAUVEGARDE (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) $_POST['id'];
            $product = $manager->find($id);

            if ($product) {
                // Mise à jour des infos de base
                $product->setName(trim($_POST['name']));
                $product->setPrice((float)$_POST['price']);

                // GESTION IMAGE (On garde l'ancienne si pas de nouvelle uploadée)
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
                    // ... (Même logique d'upload que add()) ...
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), $allowed)) {
                        $newFilename = uniqid() . '.' . $ext;
                        $dest = __DIR__ . '/../../public/img/uploads/' . $newFilename;
                        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dest)) {
                            $product->setImage('img/uploads/' . $newFilename);
                        }
                    }
                } elseif (!empty($_POST['image_url'])) {
                    $product->setImage($_POST['image_url']);
                }

                // RECONSTRUCTION DU JSON (Exactement comme dans add())
                $category = $product->getCategory(); // On garde la catégorie d'origine
                $descData = [];

                if ($category === 'Arme') {
                    $descData = [
                        'damage' => $_POST['weapon_damage'] ?? '0',
                        'lore'   => $_POST['weapon_lore'] ?? '',
                        'enchants' => !empty($_POST['weapon_enchants'])
                            ? array_map('trim', explode(',', $_POST['weapon_enchants']))
                            : []
                    ];
                } elseif ($category === 'Grade') {
                    $descData = [
                        'prefix'    => $_POST['rank_prefix'] ?? '',
                        'color'     => $_POST['rank_color'] ?? '#ffffff',
                        'coins'     => (int)($_POST['rank_coins'] ?? 0),
                        'homes'     => (int)($_POST['rank_homes'] ?? 0),
                        'slots'     => (int)($_POST['rank_slots'] ?? 0),
                        'xp'        => $_POST['rank_xp'] ?? '1.0x',
                        'fly'       => isset($_POST['rank_fly']),
                        'feed'      => isset($_POST['rank_feed']),
                        'repair'    => isset($_POST['rank_repair']),
                        'full_join' => isset($_POST['rank_full_join']),
                        'cooldown'  => isset($_POST['rank_cooldown']),
                        'particles' => isset($_POST['rank_particles'])
                    ];
                }

                $product->setDescription(json_encode($descData));
                $manager->update($product);
            }
            $this->redirect('index.php?page=admin');
            return;
        }

        // AFFICHAGE (GET)
        if (isset($_GET['id'])) {
            $product = $manager->find((int)$_GET['id']);
            if ($product) {
                // On décode le JSON pour pouvoir pré-remplir les champs dans la vue
                $data = json_decode($product->getDescription() ?? '', true);
                if (!is_array($data)) $data = []; // Sécurité

                $this->render('admin/edit', [
                    'product' => $product,
                    'data' => $data
                ]);
                return;
            }
        }

        $this->redirect('index.php?page=admin');
    }

    public function delete(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            (new ProductManager())->delete((int)$_POST['id']);
        }
        $this->redirect('index.php?page=admin');
    }
}