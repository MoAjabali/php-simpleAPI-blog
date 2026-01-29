<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

/**
 * نموذج المستخدم - يمثل جدول المستخدمين في قاعدة البيانات
 */
class User
{
    /**
     * @var PDO اتصال قاعدة البيانات
     */
    private $db;

    /**
     * @var int معرف المستخدم
     */
    public $id;

    /**
     * @var string اسم المستخدم
     */
    public $username;

    /**
     * @var string البريد الإلكتروني
     */
    public $email;

    /**
     * @var string كلمة المرور المشفرة
     */
    public $password;

    /**
     * @var string الاسم الكامل
     */
    public $full_name;

    /**
     * @var string تاريخ الإنشاء
     */
    public $created_at;

    /**
     * @var string تاريخ التحديث
     */
    public $updated_at;

    /**
     * البناء - إنشاء اتصال بقاعدة البيانات
     */
    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
    }

    /**
     * إنشاء مستخدم جديد
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        $sql = "INSERT INTO users (username, email, password, full_name, created_at) 
                VALUES (:username, :email, :password, :full_name, NOW())";
        
        $stmt = $this->db->prepare($sql);
        
        // تشفير كلمة المرور
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $stmt->execute($data);
    }

    /**
     * البحث عن مستخدم باستخدام البريد الإلكتروني
     * @param string $email
     * @return User|null
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        return $stmt->fetch();
    }

    /**
     * البحث عن مستخدم باستخدام اسم المستخدم
     * @param string $username
     * @return User|null
     */
    public function findByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        return $stmt->fetch();
    }

    /**
     * البحث عن مستخدم باستخدام المعرف
     * @param int $id
     * @return User|null
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this));
        return $stmt->fetch();
    }

    /**
     * التحقق من صحة بيانات تسجيل الدخول
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function validateLogin($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return null;
    }

    /**
     * تحديث بيانات المستخدم
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        // إذا كانت كلمة مرور جديدة، قم بتشفيرها
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['id'] = $id;
        
        $sql = "UPDATE users SET 
                username = COALESCE(:username, username),
                email = COALESCE(:email, email),
                password = COALESCE(:password, password),
                full_name = COALESCE(:full_name, full_name),
                updated_at = :updated_at
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * الحصول على جميع المستخدمين
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }

    /**
     * حذف مستخدم
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * التحقق من وجود بريد إلكتروني
     * @param string $email
     * @param int|null $excludeId
     * @return bool
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * التحقق من وجود اسم مستخدم
     * @param string $username
     * @param int|null $excludeId
     * @return bool
     */
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = ['username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }
}