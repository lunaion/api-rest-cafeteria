<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\Rol;

class RolController extends Controller
{   
    // Acceso a rutas JWT
    public function __construct() {
        $this->middleware('api.auth', ['except' => [
            'index', 
            'show', 
        ]]);
    }

    // Devuelve todos los roles
    public function index() {
        $rols = Rol::all();
        
        return response()->json([
            'code'          => 200,
            'status'        => 'success',
            'rols'    => $rols
        ]);
    }

    // Devuelve un solo rol
    public function show($id) {
        $rol = Rol::find($id);

        if (is_object($rol)) {
            $data = [
                'code'          => 200,
                'status'        => 'success',
                'rol'    => $rol
            ];
        } else {
            $data = [
                'code'          => 404,
                'status'        => 'error',
                'message'       => 'El rol no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    // Crear un nuevo rol
    public function store(Request $request) {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
        if (!empty($params_array)) {

            // Validar los datos
            $validate = Validator::make($params_array, [
                'name'         => 'required|unique:rols',
                'status'       => 'required',
            ]);

            if ($validate->fails()) {
                $data =  [
                    'code'      => 400,            
                    'status'    => 'error',            
                    'message'   => 'No se ha guardado el rol, faltan datos o el rol ya existe'        
                ];
            } else {
                // Guardar los datos
                $rol = new Rol();
                $rol->name = $params->name;
                $rol->description = $params->description;
                $rol->status = $params->status;
                $rol->save();

                $data =  [
                    'code'      => 200,            
                    'status'    => 'success',            
                    'post'      => $rol   
                ];

            }
            
        } else {
            $data =  [
                'code'      => 400,            
                'status'    => 'error',            
                'message'   => 'Algunos datos son requeridos'        
            ];
        }
        
        // Devolver los datos
        return response()->json($data, $data['code']);
    }

    // Actulaizar Rol
    public function update($id, Request $request) {

        // Recoger los datos que vengan por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validar los datos
            $validate = Validator::make($params_array, [
                'name' => 'required|unique:rols',
                'status' => 'required'
            ]);

            if ($validate->fails()) {
                $data =  [
                    'code'      => 400,            
                    'status'    => 'error',            
                    'message'   => 'No se ha guardado el rol, faltan datos o el rol ya existe.'        
                ];

                // Quitar lo no quiero actulizar
                unset($params_array['id']);
                unset($params_array['created_at']);

            } else {
                // Actulizar el registro(categoria)
                $rol = Rol::where('id', $id)->update($params_array);

                $data = [
                    'code'      => 200,
                    'status'    => 'success',
                    'rol'  => $params_array
                ];
            }

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

    // Eliminar rol
    public function destroy($id, Request $request) {

        // Conseguir el registro
        $rol = Rol::where('id', $id)->first();

        if (!empty($rol)) {
            // Borrarlo
            $rol->delete();

            // Devolver algo
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'rol'      => $rol,
            ];
        } else {
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'message'   => 'El rol con Id:' .$id. ' no existe.',
            ];
        }
        

        return response()->json($data, $data['code']);
    }
    
}
