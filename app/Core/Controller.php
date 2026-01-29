<?php

namespace App\Core;

/**
 * فئة Controller الأساسية
 * توفر وظائف مشتركة لجميع controllers في التطبيق
 * يتم وراثة هذه الفئة من قبل جميع controllers الأخرى
 */
class Controller
{
    /**
     * تحميل وعرض ملف view
     * @param string $view اسم ملف الـ view (بدون الامتداد .php)
     * @param array $data البيانات المراد تمريرها إلى الـ view
     * @return void
     */
    protected function view(string $view, array $data = []): void
    {
        // تحويل عناصر المصفوفة إلى متغيرات منفصلة
        // مثال: ['name' => 'أحمد'] تصبح متغير $name = 'أحمد'
        extract($data);

        // الحصول على مسار مجلد الـ views من الإعدادات
        $baseViewPath = Config::get('app.paths.views');
        
        // بناء المسار الكامل لملف الـ view
        $viewPath = $baseViewPath . '/' . $view . '.php';

        // التحقق من وجود ملف الـ view
        if (file_exists($viewPath)) {
            // تحميل وعرض الـ view
            require $viewPath;
            return;
        }

        // إذا كان التطبيق في وضع التصحيح، عرض معلومات عن الخطأ
        if (Config::get('app.debug')) {
            echo "<h3>View Not Found</h3>";
            echo "<p>{$view}.php</p>";
            echo "<p>Path: {$viewPath}</p>";
        }

        // إيقاف التنفيذ وعرض رسالة خطأ
        die('View not found');
    }

    /**
     * إعادة توجيه المستخدم إلى رابط معين
     * @param string $path يمكن أن يكون مسار نسبي أو رابط كامل
     * @return void
     */
    protected function redirect(string $path): void
    {
        // إذا الرابط يبدأ بـ http:// أو https:// اعتبره رابط كامل
        if (preg_match('#^https?://#', $path)) {
            $url = $path;
        } else {
            // استخدم Path::url() لتحويل المسار النسبي إلى URL كامل
            $url = Path::url($path);
        }

        // إرسال رأس HTTP لإعادة التوجيه
        header("Location: $url");
        
        // إنهاء التنفيذ فوراً بعد إعادة التوجيه
        exit;
    }

    /**
     * تحميل واستدعاء model
     * @param string $model اسم الـ model المراد تحميله
     * @return object كائن الـ model الجديد
     */
    protected function model($model)
    {
        // بناء اسم الفئة الكامل للـ model
        // مثال: 'User' تصبح 'App\Models\User'
        $modelClass = "App\\Models\\{$model}";

        // التحقق من وجود فئة الـ model
        if (class_exists($modelClass)) {
            // إنشاء وإرجاع كائن من الـ model
            return new $modelClass();
        }

        // إيقاف التنفيذ إذا لم يوجد الـ model
        die("النموذج '{$model}' غير موجود");
    }
}