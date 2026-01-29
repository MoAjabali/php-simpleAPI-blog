<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\ApiResponse;
use App\Models\Blog;

class BlogApiController extends Controller
{
    private Blog $blogModel;

    public function __construct()
    {
        $this->blogModel = $this->model('Blog');
    }

    public function index(): void
    {
        $blogs = $this->blogModel->all();
        ApiResponse::success($blogs);
    }

    public function show($id): void
    {
        $blog = $this->blogModel->findById($id);
        if (!$blog) {
            ApiResponse::error('المنشور غير موجود', 404);
        }
        ApiResponse::success($blog);
    }

    public function store(): void
    {
        $user = AuthApiController::requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['title']) || empty($data['content'])) {
            ApiResponse::error('العنوان والمحتوى مطلوبان', 400);
        }

        $blogData = [
            'user_id' => $user['user_id'],
            'title'   => $data['title'],
            'content' => $data['content']
        ];

        if ($this->blogModel->create($blogData)) {
            ApiResponse::success(null, 'تم إضافة المنشور بنجاح', 201);
        } else {
            ApiResponse::error('حدث خطأ أثناء إضافة المنشور', 500);
        }
    }

    public function update($id): void
    {
        $user = AuthApiController::requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['title']) || empty($data['content'])) {
            ApiResponse::error('العنوان والمحتوى مطلوبان', 400);
        }

        $blogData = [
            'user_id' => $user['user_id'],
            'title'   => $data['title'],
            'content' => $data['content']
        ];

        if ($this->blogModel->update($id, $blogData)) {
            ApiResponse::success(null, 'تم تحديث المنشور بنجاح');
        } else {
            ApiResponse::error('حدث خطأ أثناء تحديث المنشور أو ليس لديك صلاحية', 500);
        }
    }

    public function destroy($id): void
    {
        $user = AuthApiController::requireAuth();

        if ($this->blogModel->delete($id, $user['user_id'])) {
            ApiResponse::success(null, 'تم حذف المنشور بنجاح');
        } else {
            ApiResponse::error('حدث خطأ أثناء حذف المنشور أو ليس لديك صلاحية', 500);
        }
    }
}
