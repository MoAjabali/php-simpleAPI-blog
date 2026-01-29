<?php

namespace App\Core;

/**
 * فئة Kernel - النواة الرئيسية للتطبيق
 * مسؤولة عن معالجة جميع الطلبات الواردة وتوجيهها
 */
class Kernel
{
    /**
     * معالجة الطلب الوارد (Entry Point للتطبيق)
     * هذه الدالة هي نقطة البداية لمعالجة أي طلب يصل للتطبيق
     */
    public function handle(): void
    {
        // إعداد رؤوس CORS (Cross-Origin Resource Sharing)
        // تسمح للمواقع الأخرى بالوصول إلى الـ API الخاص بنا
        // السماح لجميع النطاقات (*) أو يمكن تحديد نطاقات معينة
        header('Access-Control-Allow-Origin: *'); 
        // الطرق المسموح بها
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        // الرؤوس المسموح بها
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        // السماح بإرسال بيانات الاعتماد (كوكيز)
        header('Access-Control-Allow-Credentials: true'); 

        // معالجة طلبات Preflight (طلبات OPTIONS المسبقة)
        // عندما يرسل المتصفح طلب OPTIONS للتحقق من صلاحية الطلب قبل الإرسال الفعلي
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200); // الرد بـ 200 OK
            exit; // إنهاء التنفيذ، لا حاجة لمزيد من المعالجة
        }

        // استخراج الـ URI (المسار المطلوب) من الطلب
        // يفترض أن يكون هناك معلمة GET تسمى 'url' تحتوي على المسار
        $uri = $_GET['url'] ?? ''; // إذا لم يكن موجوداً نستخدم سلسلة فارغة
        
        // تنظيف وتأمين الـ URI
        $uri = trim(filter_var($uri, FILTER_SANITIZE_URL), '/'); 
        // 1. filter_var: تنقية الـ URI من الأحرف غير الآمنة
        // 2. trim: إزالة الشرطة المائلة (/) من البداية والنهاية

        // توجيه الطلب إلى الراوتر المناسب بناءً على نوع الطلب
        $router = require Path::routes('api.php');

        // توجيه الطلب إلى الوجهة النهائية
        $router->direct($uri, $_SERVER['REQUEST_METHOD']);
        // 1. $uri: المسار المطلوب (مثال: 'api/users' أو 'contact')
        // 2. $_SERVER['REQUEST_METHOD']: طريقة الطلب (GET, POST, PUT, DELETE)
    }
}