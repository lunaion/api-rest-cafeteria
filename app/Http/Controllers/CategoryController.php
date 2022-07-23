<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;


class CategoryController extends Controller {

    // Controlamos las acciones por medio del token.
    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    // Devuelve todas las categorias
    public function index() {
        $category = Category::all();
        
        return response()->json([
            'code'          => 200,
            'status'        => 'success',
            'categories'    => $category
        ]);
    }

    // Devuelve una sola categoria
    public function show($id) {
        $category = Category::find($id);

        if (is_object($category)) {
            $data = [
                'code'          => 200,
                'status'        => 'success',
                'category'    => $category
            ];
        } else {
            $data = [
                'code'          => 404,
                'status'        => 'error',
                'message'       => 'La categoria no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    // Crear una categoria
    public function store(Request $request) {

        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validar los datos
            $validate = Validator($params_array, [
                'name' => 'required|unique:categories'
            ]);

            // Guadar la categoria
            if ($validate->fails()) {
                $data = [
                    'code'      => 400,
                    'status'    => 'error',
                    'message'    => 'No se ha guardado la categoría.'
                ];
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = [
                    'code'      => 200,
                    'status'    => 'success',
                    'category'    => $category
                ];
            }
        } else {
            $data = [
                'code'      => 400,
                'status'    => 'error',
                'message'    => 'Algunos campos son requeridos.'
            ];
        }

        // Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {

        // Recoger los datos que vengan por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validar los datos
            $validate = Validator($params_array, [
                'name' => 'required|unique:categories'
            ]);

            // Quitar lo no quiero actulizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            // Actulizar el registro(categoria)
            $category = Category::where('id', $id)->update($params_array);

            $data = [
                'code'      => 200,
                'status'    => 'success',
                'category'  => $params_array
            ];

        } else {
            $data = [
                'code'      => 400,
                'status'    => 'error',
                'message'    => 'Algunos campos son requeridos.'
            ];
        }
        
        // Devolver los datos
        return response()->json($data, $data['code']);
    }
}
