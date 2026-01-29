<?php
namespace App\Controller;

use App\Model\ProductManager;
use App\Entity\Weapon;
use App\Entity\Rank;

class AdminController extends AbstractController
{
    private function ensureAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            $this->redirect('index.php?page=login');
        }
    }

    public function index()
    {
        $this->ensureAdmin();

        $manager = new ProductManager();
        $products = $manager->findAll();

        $this->render('admin/index', ['products' => $products]);
    }

    public function add()
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $price = (float) $_POST['price'];
            $desc = htmlspecialchars($_POST['description']);
            $image = htmlspecialchars($_POST['image']);
            $category = $_POST['category'];

            if ($category === 'Arme') {
                $product = new Weapon($name, $price);
            } elseif ($category === 'Grade') {
                $product = new Rank($name, $price);
            } else {
                $this->redirect('index.php?page=admin');
                return;
            }

            $product->setDescription($desc);
            $product->setImage($image);

            $manager = new ProductManager();
            $manager->add($product);
        }

        $this->redirect('index.php?page=admin');
    }

    public function delete()
    {
        $this->ensureAdmin();

        if (isset($_GET['id'])) {
            $manager = new ProductManager();
            $manager->delete((int)$_GET['id']);
        }

        $this->redirect('index.php?page=admin');
    }
}