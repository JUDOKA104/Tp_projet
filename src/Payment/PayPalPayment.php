<?php
namespace App\Payment;

class PayPalPayment implements PaymentInterface
{
    public function process(float $amount, array $data): bool
    {
        $email = $data['paypal_email'] ?? '';

        // Vérification simple de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Compte PayPal invalide'];
            return false;
        }

        // Simulation API PayPal
        sleep(1);

        return true;
    }
}