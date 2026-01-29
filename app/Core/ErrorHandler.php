<?php

namespace App\Core;

class ErrorHandler
{
    public static function register(): void
    {
        if (Config::get('app.debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleException(\Throwable $e): void
    {
        http_response_code(500);

        if (Config::get('app.debug')) {
            echo "<pre>{$e}</pre>";
        } else {
            require Path::views('errors/500.php');
        }
    }
}
