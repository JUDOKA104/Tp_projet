<?php
namespace App\Controller;

use App\Model\UserManager;
use App\Entity\User;
use App\Model\ProductManager;

class AuthController extends AbstractController
{
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                die("Erreur CSRF");
            }

            $pseudo = trim($_POST['pseudo'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!empty($pseudo) && !empty($email) && !empty($password)) {
                $user = new User($pseudo, $email, $password);
                $manager = new UserManager();

                if ($manager->save($user)) {
                    $this->addFlash('success', 'Compte créé ! Connectez-vous.');
                    $this->redirect('index.php?page=login');
                    return;
                } else {
                    $this->addFlash('danger', 'Cet email est déjà utilisé.');
                }
            }
        }
        $this->render('auth/register');
    }

    public function login(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                die("Erreur CSRF");
            }

            $email = $_POST['email'] ?? '';
            $passwordInput = $_POST['password'] ?? '';

            $manager = new UserManager();
            $user = $manager->findByEmail($email);

            // --- RECONSTRUCTION DU POIVRE ---

            $secretKey = "CubicInfrastructure_Super_Secret_Key_!2026";
            $hashedKey = hash('sha256', $secretKey);

            // Concaténation
            $passwordToVerify = $passwordInput . $hashedKey;

            // password_verify va comparer ce gros bloc avec le hash Argon2 stocké en BDD
            if ($user && password_verify($passwordToVerify, $user->getPassword())) {

                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'pseudo' => $user->getPseudo(),
                    'role' => $user->getRole()
                ];

                $this->addFlash('success', "Bon retour, " . $user->getPseudo());
                $this->redirect('index.php?page=boutique');
                return;
            } else {
                $error = "Identifiants incorrects !";
            }
        }
        $this->render('auth/login', ['error' => $error]);
    }

    public function profile(): void
    {
        // Sécurité : Être connecté
        if (empty($_SESSION['user'])) {
            $this->redirect('index.php?page=login');
        }

        $userId = (int)$_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];
        $myProducts = [];

        // Logique "Admin possède tout" vs "User possède son inventaire"
        if ($role === 'ADMIN') {
            $myProducts = (new ProductManager())->findAll(); // L'admin voit tout le catalogue
        } else {
            $myProducts = (new UserManager())->getInventory($userId); // Le user voit ses achats
        }

        $this->render('auth/profile', [
            'user' => $_SESSION['user'],
            'products' => $myProducts
        ]);
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('index.php?page=home');
    }
}