<?php
namespace App\Core;

/**
 * فئة Path - مسؤولة عن إدارة المسارات والروابط في المشروع
 * توفر طرق ثابتة للحصول على مسارات المجلدات والروابط الديناميكية
 */
class Path
{
    /**
     * الحصول على المسار الجذري للمشروع
     * dirname(__DIR__, 2) ترجع المسار الرئيسي بتراجع مستويين للأعلى
     */
    public static function root(): string
    {
        return dirname(__DIR__, 2);
    }

    
    /**
     * الحصول على مسار مجلد الإعدادات (config)
     * @param string $file اسم الملف المطلوب (اختياري)
     * @return string المسار الكامل للمجلد أو الملف
     */
    public static function config(string $file = ''): string
    {
           
        return self::root() . '/config' . ($file ? '/' . $file : '');
    }

      /**
     * الحصول على مسار مجلد العروض (views)
     * @param string $file اسم الملف المطلوب (اختياري)
     * @return string المسار الكامل للمجلد أو الملف
     */
    public static function views(string $file = ''): string
    {
        return self::root() . '/app/Views' . ($file ? '/' . $file : '');
    }

    /**
     * الحصول على مسار المجلد العام (public)
     * @param string $file اسم الملف المطلوب (اختياري)
     * @return string المسار الكامل للمجلد أو الملف
     */
    public static function public(string $file = ''): string
    {
        return self::root() . '/public' . ($file ? '/' . $file : '');
    }

    /**
     * الحصول على مسار مجلد المسارات (routes)
     * @param string $file اسم الملف المطلوب (اختياري)
     * @return string المسار الكامل للمجلد أو الملف
     */
    public static function routes(string $file = ''): string
    {
        return self::root() . '/routes' . ($file ? '/' . $file : '');
    }

    /**
     * إنشاء رابط المشروع الأساسي (Base URL) ديناميكياً
     * يحسب البروتوكول والمجال ومسار المشروع تلقائياً
     */
    public static function baseUrl(): string
    {
        // تحديد البروتوكول المستخدم (http أو https)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST']; // اسم المضيف (مثال: localhost)

        // استخراج اسم المشروع من SCRIPT_NAME
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // تحويل المسارات Windows-style
        $scriptDir = dirname($scriptName); // الحصول على مجلد السكريبت

        // إزالة /public من المسار لمعرفة مجلد المشروع الرئيسي
        $projectFolder = preg_replace('#/public$#', '', $scriptDir);

        // إذا كان المشروع في الجذر، نجعل المسار فارغاً
        if ($projectFolder === '/' || $projectFolder === '\\') {
            $projectFolder = '';
        }

        // إرجاع الرابط الأساسي مع إزالة أي / زائدة في النهاية
        return rtrim($protocol . '://' . $host . $projectFolder, '/');
    }

    /**
     * توليد رابط كامل لأي مسار داخل المشروع
     * @param string $path المسار النسبي المطلوب (مثال: 'users/profile')
     * @return string الرابط الكامل
     */
    public static function url(string $path = ''): string
    {
        $baseUrl = self::baseUrl();
        $path = ltrim($path, '/'); // إزالة / من بداية المسار لتجنب المسارات المزدوجة
        return $baseUrl . ($path ? '/' . $path : '');
    }
}