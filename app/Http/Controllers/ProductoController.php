<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{       
    // Crear producto
    public function store(Request $request) {

        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validar los datos
            $validate = Validator($params_array, [
                'descripcion' => 'required|min:3|unique:productos',
                'precio' => 'required',
                'stock' => 'required'
            ]);

            // Guadar los productos
            if ($validate->fails()) {
                $data = [
                    'code'      => 400,
                    'status'    => 'error',
                    'message'    => 'No se ha podico crear el producto.'
                ];
            } else {
                $producto = new Producto();
                $producto->descripcion = $params_array['descripcion'];
                $producto->precio = $params_array['precio'];
                $producto->stock = $params_array['stock'];
                $producto->save();

                $data = [
                    'code'      => 200,
                    'status'    => 'success',
                    'producto'    => $producto
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

    // Lista de todos los productos
    public function index() {
        $productos = Producto::all();
        
        return response()->json([
            'code'          => 200,
            'status'        => 'success',
            'productos'    => $productos
        ]);

    }

    // Lista de productos por ID
    public function show($id) {
        $producto = Producto::find($id);

        if (is_object($producto)) {
            $data = [
                'code'          => 200,
                'status'        => 'success',
                'producto'    => $producto
            ];
        } else {
            $data = [
                'code'          => 404,
                'status'        => 'error',
                'message'       => 'El producto con el Id:'.$id.' no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    // Actualizar producto
    public function update($id, Request $request) {

        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validar los datos
            $validate = Validator($params_array, [
                'descripcion' => 'required|min:3|unique:productos',
                'precio' => 'required',
            ]);

            // Quitar lo no quiero actualizar
            unset($params_array['id']);
            unset($params_array['stock']);
            unset($params_array['created_at']);

            // Actulizar el registro(categoria)
            $producto = Producto::where('id', $id)->update($params_array);

            $data = [
                'code'      => 200,
                'status'    => 'success',
                'producto'  => $params_array,
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

    // Eliminar producto
    public function destroy($id) {

        // Conseguir el registro
        $producto = Producto::find($id);

        if (!empty($producto)) {
            // Borrarlos
            $producto->delete();

            // Devolver algo
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'message'      => 'El producto ' .$producto->descripcion. ' ha sido elimnado correctamente'
            ];
        } else {
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'message'   => 'El producto con Id:'.$id.' no existe'
            ];
        }
        
        return response()->json($data, $data['code']);
    }

}
