<?php

namespace App\Core;

/**
 * فئة Config - مسؤولة عن تحميل وإدارة إعدادات التطبيق
 * تصميم Singleton باستخدام طرق ثابتة لتخزين واسترجاع الإعدادات
 */
class Config
{
    /**
     * مصفوفة تخزن جميع عناصر الإعدادات
     * @var array
     */
    private static array $items = [];


    /**
     * تحميل ملفات الإعدادات من مجلد config/
     * تقوم بتحميل الملفات وتخزينها في المصفوفة الثابتة $items
     * 
     * @return void
     */
    public static function load(): void
    {
        // تحميل إعدادات التطبيق العامة من app.php
        self::$items['app'] = require Path::config('app.php');
        
        // تحميل إعدادات قاعدة البيانات من database.php
        self::$items['database'] = require Path::config('database.php');
        
        // يمكن إضافة ملفات إعدادات أخرى هنا عند الحاجة
        // مثال: self::$items['email'] = require Path::config('email.php');
    }


    /**
     * استرجاع قيمة من ملفات الإعدادات باستخدام الترميز النقطي
     * 
     * @param string $key المفتاح الذي يحتوي على القيمة (مثال: 'app.name')
     * @param mixed $default القيمة الافتراضية التي ترجع إذا لم تجد القيمة
     * @return mixed القيمة المطلوبة أو القيمة الافتراضية
     */
    public static function get(string $key, $default = null)
    {
        // تقسيم المفتاح إلى أجزاء باستخدام النقطة
        // مثال: 'app.name' تصبح ['app', 'name']
        $keys = explode('.', $key);
        $value = self::$items; // البدء من مصفوفة الإعدادات الرئيسية

        // البحث في المصفوفة متعددة الأبعاد
        foreach ($keys as $segment) {
            // إذا لم يكن المفتاح موجوداً، نرجع القيمة الافتراضية
            if (!isset($value[$segment])) {
                return $default;
            }
            // الانتقال إلى المستوى التالي
            $value = $value[$segment];
        }

        return $value;
    }
}