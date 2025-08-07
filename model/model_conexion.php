<?php
class Database
{
    private static $instancia = null;
    private $pdo;

    private function __construct()
    {
        require_once __DIR__ . '/../config/database.php';
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        } catch (PDOException $e) {
            error_log('Error de conexión a la BD: ' . $e->getMessage());
            die('Error crítico: No se pudo conectar a la base de datos.');
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    private function __clone()
    {
    }
}