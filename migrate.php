<?php
require_once 'autoload.php';
require_once __DIR__ . '/config/database.php';

use App\Core\Config;

try {
    Config::load();
    $dbConfig = require __DIR__ . '/config/database.php';
    
    // 1. الاتصال بدون تحديد قاعدة البيانات لإنشائها إذا لم تكن موجودة
    $dsn = "mysql:host=" . $dbConfig['host'] . ";charset=" . $dbConfig['charset'];
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dbName = $dbConfig['database'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbName' checked/created successfully.\n";
    
    // 2. الاتصال بقاعدة البيانات المحددة
    $pdo->exec("USE `$dbName` ");

    // 3. إنشاء جدول المستخدمين
    $sqlUsers = "CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL UNIQUE,
      `email` varchar(100) NOT NULL UNIQUE,
      `password` varchar(255) NOT NULL,
      `full_name` varchar(100) DEFAULT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sqlUsers);
    echo "Table 'users' created successfully!\n";

    // 4. إنشاء جدول المدونات
    $sqlBlogs = "CREATE TABLE IF NOT EXISTS `blogs` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `title` varchar(255) NOT NULL,
      `content` text NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sqlBlogs);
    echo "Table 'blogs' created successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
