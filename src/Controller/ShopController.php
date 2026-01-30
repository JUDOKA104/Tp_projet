<?php
namespace App\Controller;

use App\Model\ProductManager;
use App\Payment\CreditCardPayment;
use App\Payment\PayPalPayment;
use App\Model\UserManager;

class ShopController extends AbstractController
{
    /**
     * Affiche la boutique
     */
    public function index(): void
    {
        $productManager = new ProductManager();
        $products = $productManager->findAll();

        $this->render('shop/index', [
            'products' => $products
        ]);
    }

    /**
     * Traite l'achat via le Pattern Strategy
     */
    public function buy(): void
    {
        // Sécurité : Utilisateur connecté uniquement
        if (empty($_SESSION['user'])) {
            $this->redirect('index.php?page=login');
        }

        // 2. BLOCAGE ADMIN (Considéré comme ayant déjà tout)
        if ($_SESSION['user']['role'] === 'ADMIN') {
            $this->addFlash('info', "👑 Vous êtes Admin, vous possédez déjà tous les objets !");
            $this->redirect('index.php?page=boutique');
            exit;
        }

        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $productId = (int)($_POST['product_id'] ?? 0);
            $method = $_POST['payment_method'] ?? '';

            // Récupération du produit
            $productManager = new ProductManager();
            $product = $productManager->find($productId);

            if (!$product) {
                $this->addFlash('danger', "Produit introuvable.");
                $this->redirect('index.php?page=boutique');
            }

            // Choix de la stratégie de paiement
            $paymentStrategy = match ($method) {
                'card'   => new CreditCardPayment(),
                'paypal' => new PayPalPayment(),
                default  => null
            };

            if ($paymentStrategy) {
                // Exécution du paiement (Polymorphisme)
                // On passe le prix et toutes les données du formulaire ($_POST)
                $success = $paymentStrategy->process($product->getPrice(), $_POST);

                if ($success) {
                    // SAUVEGARDE EN BDD
                    $userManager = new UserManager();
                    $userManager->addToInventory((int)$_SESSION['user']['id'], $product->getId());

                    $this->addFlash('success', "✅ Achat validé ! " . $product->getName() . " ajouté à votre profil.");
                }
            } else {
                $this->addFlash('danger', "Méthode de paiement non reconnue.");
            }
        }

        $this->redirect('index.php?page=boutique');
    }
}