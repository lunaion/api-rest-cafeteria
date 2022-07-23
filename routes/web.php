<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

// RUTAS DEL FRAMEWORK LARAVEL
Route::get('/', function () {
    return view('welcome');
});


/*** RUTAS DE LA API ***/

    /**
     * Metodos HTTP comunes
     * GET: Conseguir datos o recursos
     * POST: Guardar datos o recursos o hacer lógica desde un formulario
     * PUT: Actulizar datos o recursos
     * DELETE: Eliminar datos o recursos
     */


    // Rutas de prueba
    /* Route::get('/usuario/pruebas', 'App\Http\Controllers\UserController@pruebas'); */

    // Rutas de tipo resurces del controlador de categorias
    Route::resource('/api/category', 'App\Http\Controllers\CategoryController');

    // Rutas de tipo resurces del controlador de posts
    Route::resource('/api/post', 'App\Http\Controllers\PostController');
    Route::post('/api/post/upload', 'App\Http\Controllers\PostController@upload');
    Route::get('/api/post/image/{filename}', 'App\Http\Controllers\PostController@getImage');
    Route::get('/api/post/category/{id}', 'App\Http\Controllers\PostController@getPostByCategory');
    Route::get('/api/post/user/{id}', 'App\Http\Controllers\PostController@getPostsByUser');

    // Rutas de tipo resurces del controlador de roles
    Route::resource('/api/rol', 'App\Http\Controllers\RolController');

    // Rutas del controlador de usuarios
    Route::post('/api/register', 'App\Http\Controllers\UserController@register');
    Route::post('/api/login', 'App\Http\Controllers\UserController@login');
    Route::put('/api/user/update', 'App\Http\Controllers\UserController@update');
    Route::post('/api/user/upload', 'App\Http\Controllers\UserController@upload');
    Route::get('/api/user/avatar/{filename}', 'App\Http\Controllers\UserController@getImage');
    Route::get('/api/user/detail/{id}', 'App\Http\Controllers\UserController@detail')->middleware(ApiAuthMiddleware::class);


