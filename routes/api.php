<?php
// routes/api.php
use App\Core\Router;

// إنشاء كائن الراوتر
$router = new Router();

// مسارات المصادقة عبر API
$router->post('api/login', 'AuthApiController@login');
$router->post('api/register', 'AuthApiController@register');
$router->post('api/logout', 'AuthApiController@logout');

// Blogs CRUD
$router->get('api/blogs', 'BlogApiController@index');
$router->get('api/blogs/{id}', 'BlogApiController@show');
$router->post('api/blogs', 'BlogApiController@store');
$router->put('api/blogs/{id}', 'BlogApiController@update');
$router->delete('api/blogs/{id}', 'BlogApiController@destroy');


return $router;
