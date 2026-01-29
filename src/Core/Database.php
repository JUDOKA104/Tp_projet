<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = 'localhost';
            $port = '5432';
            $db   = 'Tp_projet';
            $user = 'postgres';
            $pass = 'root';

            $dsn = "pgsql:host=$host;port=$port;dbname=$db";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Erreur de connexion BDD : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}