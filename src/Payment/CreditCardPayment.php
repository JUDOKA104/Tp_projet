<?php
namespace App\Payment;

class CreditCardPayment implements PaymentInterface
{
    public function process(float $amount, array $data): bool
    {
        // Simulation de vérification format basique
        $cardNumber = str_replace(' ', '', $data['card_number'] ?? '');
        $cvv = $data['cvv'] ?? '';

        // Règle de la démo : Il faut 16 chiffres et un CVV de 3 chiffres
        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Numéro de carte invalide (16 chiffres requis)'];
            return false;
        }

        if (!preg_match('/^\d{3}$/', $cvv)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'CVV invalide'];
            return false;
        }

        // Simulation de l'appel API vers la banque
        sleep(1);

        // On accepte le paiement (Dans la vraie vie, Stripe renverrait OK ici)
        return true;
    }
}