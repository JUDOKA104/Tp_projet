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
        $this->render('admin/index', ['products' => (new ProductManager())->findAll()]);
    }

    public function add(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                die("Erreur de sécurité : Token CSRF invalide.");
            }

            $category = $_POST['category'];
            $name = trim($_POST['name']);
            $price = (float)$_POST['price'];

            // Création dynamique selon la catégorie
            $product = ($category === 'Arme') ? new Weapon($name, $price) : new Rank($name, $price);

            // Gestion propre via méthodes privées
            $product->setImage($this->handleImageUpload());
            $product->setDescription($this->buildJsonDescription($category));

            (new ProductManager())->add($product);

            $this->addFlash('success', "Le produit $name a été ajouté au catalogue !");
            $this->redirect('index.php?page=admin');
        }
    }

    public function edit(): void
    {
        $this->ensureAdmin();
        $manager = new ProductManager();

        // 1. TRAITEMENT DU FORMULAIRE
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                die("Erreur de sécurité : Token CSRF invalide.");
            }

            $product = $manager->find((int)$_POST['id']);

            if ($product) {
                $product->setName(trim($_POST['name']));
                $product->setPrice((float)$_POST['price']);

                // On met à jour l'image SEULEMENT si une nouvelle est envoyée
                $newImage = $this->handleImageUpload();
                if ($newImage) {
                    $product->setImage($newImage);
                } elseif (!empty($_POST['image_url'])) {
                    $product->setImage($_POST['image_url']);
                }

                $product->setDescription($this->buildJsonDescription($product->getCategory()));
                $manager->update($product);

                $this->addFlash('success', "Modifications enregistrées pour " . $product->getName());
            }
            $this->redirect('index.php?page=admin');
            return;
        }

        // 2. AFFICHAGE DE LA PAGE
        if (isset($_GET['id'])) {
            $product = $manager->find((int)$_GET['id']);
            if ($product) {
                $this->render('admin/edit', [
                    'product' => $product,
                    'data' => json_decode($product->getDescription() ?? '', true) ?? []
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
            if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                die("Erreur de sécurité : Token CSRF invalide.");
            }

            (new ProductManager())->delete((int)$_POST['id']);
            $this->addFlash('danger', "Produit supprimé définitivement.");
        }
        $this->redirect('index.php?page=admin');
    }

    /**
     * Gère l'upload de fichier et retourne le chemin relatif
     */
    private function handleImageUpload(): ?string
    {
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowed)) {
                $filename = uniqid() . '.' . $ext;
                $destDir = __DIR__ . '/../../public/img/uploads/';

                if (!is_dir($destDir)) mkdir($destDir, 0777, true);

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destDir . $filename)) {
                    return 'img/uploads/' . $filename;
                }
            }
        }
        // Fallback sur l'URL directe uniquement lors de l'ajout
        return $_POST['image_url'] ?? null;
    }

    /**
     * Construit le JSON selon la catégorie
     */
    private function buildJsonDescription(string $category): string
    {
        $data = [];

        if ($category === 'Arme') {
            $data = [
                'damage' => $_POST['weapon_damage'] ?? '0',
                'lore'   => $_POST['weapon_lore'] ?? '',
                'enchants' => !empty($_POST['weapon_enchants'])
                    ? array_map('trim', explode(',', $_POST['weapon_enchants']))
                    : []
            ];
        } elseif ($category === 'Grade') {
            $data = [
                'prefix'    => $_POST['rank_prefix'] ?? '',
                'color'     => $_POST['rank_color'] ?? '#ffffff',
                'coins'     => (int)($_POST['rank_coins'] ?? 0),
                'homes'     => (int)($_POST['rank_homes'] ?? 0),
                'slots'     => (int)($_POST['rank_slots'] ?? 0),
                'xp'        => $_POST['rank_xp'] ?? '1.0x',
                // Checkboxes : Si présente dans $_POST = true, sinon false
                'fly'       => isset($_POST['rank_fly']),
                'feed'      => isset($_POST['rank_feed']),
                'repair'    => isset($_POST['rank_repair']),
                'full_join' => isset($_POST['rank_full_join']),
                'cooldown'  => isset($_POST['rank_cooldown']),
                'particles' => isset($_POST['rank_particles'])
            ];
        }

        return json_encode($data);
    }
}