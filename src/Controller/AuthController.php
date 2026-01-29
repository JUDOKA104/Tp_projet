<?php
namespace App\Controller;

use App\Model\UserManager;
use App\Entity\User;

class AuthController extends AbstractController
{
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = trim($_POST['pseudo'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!empty($pseudo) && !empty($email) && !empty($password)) {
                $user = new User($pseudo, $email, $password);

                $manager = new UserManager();
                try {
                    if ($manager->save($user)) {
                        $this->redirect('index.php?page=login');
                        return;
                    }
                } catch (\Exception $e) {
                    echo "Erreur lors de l'inscription (Email peut-être déjà pris).";
                }
            }
        }

        $this->render('auth/register');
    }

    public function login(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $manager = new UserManager();
            $user = $manager->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'pseudo' => $user->getPseudo(),
                    'role' => $user->getRole()
                ];

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