<?php

/**
 * Sécurise une chaine de caractères pour l'affichage HTML (Protection XSS)
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug dump and die
 */
function dd($var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

/**
 * Génère un jeton de sécurité unique pour la session en cours
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        try {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie si le jeton reçu correspond à celui de la session
 */
function verifyCsrfToken(?string $token): bool {
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}