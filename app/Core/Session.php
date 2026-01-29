<?php
namespace App\Core;

/**
 * كلاس الجلسات (Sessions) - إدارة الجلسات بسهولة
 */
class Session
{
    /**
     * بدء الجلسة إذا لم تكن بدأت
     */
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * تعيين قيمة في الجلسة
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * الحصول على قيمة من الجلسة
     * @param string $key
     * @return mixed|null
     */
    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * التحقق من وجود مفتاح في الجلسة
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * حذف مفتاح من الجلسة
     * @param string $key
     * @return void
     */
    public static function delete($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * تدمير الجلسة بالكامل (تسجيل الخروج)
     * @return void
     */
    public static function destroy()
    {
        // إفراغ مصفوفة الجلسة
        $_SESSION = [];
        
        // إذا تم تعيين كوكيز الجلسة، قم بحذفه
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // تدمير الجلسة
        session_destroy();
    }

    /**
     * تعيين رسالة فلاش (تظهر مرة واحدة)
     * @param string $key
     * @param string $message
     * @return void
     */
    public static function setFlash($key, $message)
    {
        self::set('flash_' . $key, $message);
    }

    /**
     * الحصول على رسالة فلاش وحذفها
     * @param string $key
     * @return string|null
     */
    public static function getFlash($key)
    {
        $flashKey = 'flash_' . $key;
        $message = self::get($flashKey);
        self::delete($flashKey);
        return $message;
    }

    /**
     * التحقق من وجود رسالة فلاش
     * @param string $key
     * @return bool
     */
    public static function hasFlash($key)
    {
        return self::has('flash_' . $key);
    }
}