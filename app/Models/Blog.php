<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class Blog
{
    private $db;

    public $id;
    public $user_id;
    public $title;
    public $content;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO blogs (user_id, title, content, created_at) 
                VALUES (:user_id, :title, :content, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function findById($id)
    {
        $sql = "SELECT blogs.*, users.full_name as author 
                FROM blogs 
                JOIN users ON blogs.user_id = users.id 
                WHERE blogs.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function all()
    {
        $sql = "SELECT blogs.*, users.full_name as author 
                FROM blogs 
                JOIN users ON blogs.user_id = users.id 
                ORDER BY blogs.created_at DESC";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE blogs SET 
                title = :title,
                content = :content,
                updated_at = NOW()
                WHERE id = :id AND user_id = :user_id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id, $user_id)
    {
        $sql = "DELETE FROM blogs WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'user_id' => $user_id]);
    }
}
