<?php

/**
 * Sécurise une chaine de caractères pour l'affichage HTML (Protection XSS)
 * Raccourci pour htmlspecialchars
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug dump and die (utile pour le dev)
 */
function dd($var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}