<?php

namespace App\Core;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'تمت العملية بنجاح',
        int $status = 200
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    public static function error(
        string $message = 'حدث خطأ',
        int $status = 400,
        mixed $errors = null
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'status'  => $status,
            'message' => $message,
            'errors'  => $errors
        ]);
        exit;
    }
}
