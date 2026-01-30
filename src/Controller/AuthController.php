<?php
namespace App\Controller;

use App\Model\UserManager;
use App\Entity\User;

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
            $password = $_POST['password'] ?? '';

            $manager = new UserManager();
            $user = $manager->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                session_regenerate_id(true); // Sécurité anti-fixation de session
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

    public function logout(): void
    {
        session_destroy();
        $this->redirect('index.php?page=home');
    }
}