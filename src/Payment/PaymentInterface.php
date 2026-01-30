<?php
namespace App\Payment;

interface PaymentInterface
{
    /**
     * Traite le paiement.
     * @param float $amount Le montant à payer
     * @param array $data Les données du formulaire (numéro carte, email...)
     * @return bool Succès ou échec
     */
    public function process(float $amount, array $data): bool;
}