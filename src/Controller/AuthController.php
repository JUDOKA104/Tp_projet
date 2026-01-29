<?php
namespace App\Controller;

use App\Model\UserManager;
use App\Entity\User;

class AuthController extends AbstractController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $user = new User($pseudo, $email, $password);

            $manager = new UserManager();
            try {
                $manager->save($user);

                $this->redirect('index.php?page=login');
            } catch (\Exception $e) {
                echo "Erreur d'inscription : " . $e->getMessage();
            }
        }

        $this->render('auth/register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $manager = new UserManager();
            $user = $manager->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'pseudo' => $user->getPseudo(),
                    'role' => $user->getRole()
                ];

                $this->redirect('index.php?page=boutique');
            } else {
                $error = "Identifiants incorrects !";
            }
        }

        $this->render('auth/login', ['error' => $error ?? null]);
    }
    public function logout()
    {
        session_destroy();
        $this->redirect('index.php?page=home');
    }
}