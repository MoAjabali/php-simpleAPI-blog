<?php

/**
 * نظام التحميل التلقائي للكلاسات
 */
spl_autoload_register(function ($className) {
    // إزالة App\ من بداية الكلاس إذا وجدت
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    if (strncmp($prefix, $className, strlen($prefix)) === 0) {
        $relativeClass = substr($className, strlen($prefix));
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // البحث العام (للكلاسات التي لا تتبع namespace App)
    $file = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});