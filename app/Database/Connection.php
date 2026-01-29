<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Core\Config;

/**
 * كلاس الاتصال بقاعدة البيانات باستخدام PDO
 * Singleton Pattern
 */
class Connection
{
    /**
     * نسخة وحيدة من الكلاس
     */
    private static ?Connection $instance = null;

    /**
     * كائن PDO
     */
    private PDO $connection;

    /**
     * مُنشئ خاص (Singleton)
     */
    private function __construct()
    {
        $db = Config::get('db');

        try {
            $this->connection = new PDO(
                Config::get('database.driver') .
                    ":host=" . Config::get('database.host') .
                    ";dbname=" . Config::get('database.database') .
                    ";charset=" . Config::get('database.charset'),

                Config::get('database.username'),
                Config::get('database.password')
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {

            if (Config::get('app.debug')) {
                throw new PDOException(
                    'Database connection failed: ' . $e->getMessage(),
                    (int) $e->getCode()
                );
            }

            throw new PDOException('Database connection error');
        }
    }

    /**
     * الحصول على نسخة واحدة من الاتصال
     */
    public static function getInstance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * الحصول على كائن PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * منع النسخ
     */
    private function __clone() {}

    /**
     * منع unserialize
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}
