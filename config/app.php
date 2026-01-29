<?php
use App\Core\Path;

return [
    'name' => 'مدونتي البسيطة',
    'debug' => true,
    'charset' => 'UTF-8',
    'paths' => [
        'views' => Path::views(),
    ],
    
    'app_name' => 'Simple Blog',
    'app_url' => 'http://localhost/public',

    // إعدادات JWT
    'jwt_secret' => 'مفتاح_سري_طويل_وصعب_التخمين_جداً',
    'jwt_exp' => 3600 // بالثواني
];
