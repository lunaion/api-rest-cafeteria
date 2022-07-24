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

    // Rutas no protegidas
    Route::post('/api/register', 'App\Http\Controllers\UserController@register');
    Route::post('/api/login', 'App\Http\Controllers\UserController@login');

    // Rutas protegidas
    Route::group(['middleware' => ['api.auth']], function() {
        
        // Productos.
        Route::get('api/productos', 'App\Http\Controllers\ProductoController@index');
        Route::get('api/producto/{id}', 'App\Http\Controllers\ProductoController@show');
        Route::post('api/producto', 'App\Http\Controllers\ProductoController@store');
        Route::put('api/producto/{id}', 'App\Http\Controllers\ProductoController@update');
        Route::delete('api/producto/{id}', 'App\Http\Controllers\ProductoController@destroy');

        // Usuarios.
        Route::put('/api/user/update', 'App\Http\Controllers\UserController@update');
        Route::post('/api/user/upload', 'App\Http\Controllers\UserController@upload');
        Route::get('/api/user/avatar/{filename}', 'App\Http\Controllers\UserController@getImage');
        Route::get('/api/users', 'App\Http\Controllers\UserController@index');
        Route::get('/api/user/detail/{id}', 'App\Http\Controllers\UserController@detail');
        Route::delete('api/user/{id}', 'App\Http\Controllers\UserController@destroy');

    });

