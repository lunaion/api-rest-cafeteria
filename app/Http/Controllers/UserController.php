<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(Request $request) {

        // Recoger los datos del usuario por post
        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array

        if (!empty($params) && !empty($params_array)) {
            
            // Limpiar los datos
            $params_array = array_map('trim', $params_array);
    
            // Validar datos
            $validate = Validator($params_array, [
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => 'required',
            ]);
            
            if ($validate->fails()) {
                // la validación ha fallado
                $data = array(
                    'status'    => 'error',
                    'code'      => 404,
                    'message'   => 'El usuario no se ha creado',
                    'errors'    => $validate->errors()
                );
            } else {
                // Validación pasada correctamente

                // Cifrar la contraseña
                $pwd = hash('sha256', $params->password);

                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->email = $params_array['email'];
                $user->password = $pwd;

                // Guardar el usuario
                $user->save();

                $data = array(
                    'status'    => 'success',
                    'code'      => 200,
                    'message'   => 'El usuario se ha creado correctamente',
                    'user'      => $user
                );
            }

        } else {
            $data = array(
                'status'    => 'error',
                'code'      => 404,
                'message'   => 'Los datos enviados no son correctos'
            );
        }

        return response()->json($data, $data['code']);
    }


    public function login(Request $request) {

        $jwtAuth = new JwtAuth();

        // Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar esos datos
        $validate = Validator($params_array, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        
        if ($validate->fails()) {
            // la validación ha fallado
            $signup = array(
                'status'    => 'error',
                'code'      => 404,
                'message'   => 'El usuario no se ha podico identificar',
                'errors'    => $validate->errors()
            );
        } else {
            // Cifrar la password
            $pwd = hash('sha256', $params->password);

            // Devolver token o datos
            $signup = $jwtAuth->signup($params->email, $pwd);

            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }

        return response()->json($signup, 200);
    }

    public function update(Request $request) {

        // Comprobar si el usuario está indentificado
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {
            
            // Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);
            
            // Validar los datos
            $validate = Validator($params_array, [
                'name'      => 'required',
                'email'     => 'required|email|unique:users,'.$user->sub
            ]);

            // Quitar los campos que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['password']);
            unset($params_array['created_add']);
            unset($params_array['remember_token']);

            // Actualizar el usuario en la DB
            $user_update = User::where('id', $user->sub)->update($params_array);

            // Devolver array con resultado
            $data = array(
                'code'      => 200,
                'status'    => 'success',
                'user'      => $user,
                'changes'   => $params_array
            );

        } else {
            $data = array(
                'code'      => 400,
                'status'    => 'error',
                'message'   => 'El usuario no está indentificado.'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function upload(Request $request) {

        // Recoger los datos de la petición
        $image = $request->file('file0');

        // Validación de imagen
        $validate = Validator($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        // Guardar imagen
        if (!$image || $validate->fails()) {
            $data = array(
                'code'      => 400,
                'status'    => 'error',
                'message'   => 'Error al subir imagen'
            );  
        } else {
            $image_name = time().$image->getClientOriginalName();
            Storage::disk('users')->put($image_name, File::get($image));

            $data = array(
                'code'      => 200,
                'status'    => 'success',
                'image'     => $image_name
            );
            
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename) {
        $isset = Storage::disk('users')->exists($filename);
        if ($isset) {
            $file = Storage::disk('users')->get($filename);
            return new Response($file, 200);
        } else {
            $data = array(
                'code'      => 404,
                'status'    => 'error',
                'message'     => 'La imagen no existe.'
            );

            return response()->json($data, $data['code']);
        }
        
    }

    public function detail($id) {
        $user = User::find($id);

        if (is_object($user)) {
            $data = array(
                'code'      => 200,
                'status'    => 'success',
                'user'      => $user
            );
        } else {
            $data = array(
                'code'      => 404,
                'status'    => 'error',
                'messge'      => 'El usuario con el id:'.$id.' no existe.'
            );
        }
        
        return response()->json($data, $data['code']);
    }

    public function index() {
        $user = User::all();

        if (is_object($user)) {
            $data = array(
                'code'      => 200,
                'status'    => 'success',
                'user'      => $user
            );
        } else {
            $data = array(
                'code'      => 404,
                'status'    => 'error',
                'messge'      => 'No se encontraron registros.'
            );
        }
        
        return response()->json($data, $data['code']);
    }

    // Eliminar usuario
    public function destroy($id) {

        // Conseguir el registro
        $user = User::find($id);

        if (!empty($user)) {
            // Borrarlos
            $user->delete();

            // Devolver algo
            $data = [
                'code'      => 200,
                'status'    => 'success',
                'message'      => 'El usuario ' .$user->name. ' ha sido elimnado correctamente'
            ];
        } else {
            $data = [
                'code'      => 404,
                'status'    => 'error',
                'message'   => 'El usuario con Id:'.$id.' no existe'
            ];
        }
        
        return response()->json($data, $data['code']);
    }
}
