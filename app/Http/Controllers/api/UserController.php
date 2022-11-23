<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $input = $request->only(['name', 'email', 'password', 'address', 'birthdate', 'city']);

        $validate_data = [
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'address'  => 'required',
            'birthdate'  => 'required',
            'city'  => 'required',

        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al intentar registrarse.',
                'errors' => $validator->errors()
            ]);
        }
 
        try{

            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'address' => $input['address'],
                'birthdate' => $input['birthdate'],
                'city' => $input['city'],
            ];
            
            $user = User::create($data);
            

            if (auth()->attempt($input)) {
                $token = auth()->user()->createToken('passport_token')->accessToken;
                
                return response()->json([
                    'success' => true,
                    'data' => $user,
                    'message' => 'Usuario registrado con exito.',
                    'token' => $token
                ], 200);
            }

        } catch(QueryException $e){
            return response()->json([
                'success' => false,
                'message' => 'Error de inserción de datos sql.',
                'errors' => $e
            ]);
        }
    }


   

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['id', 'name', 'email', 'password', 'address', 'birthdate', 'city']);

        $validate_data = [
            'id' => 'required',
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'address'  => 'required',
            'birthdate'  => 'required',
            'city'  => 'required',

        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al intentar registrarse.',
                'errors' => $validator->errors()
            ]);
        }
        $id = $input['id'];
 
        try{

            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'address' => $input['address'],
                'birthdate' => $input['birthdate'],
                'city' => $input['city'],
            ];

            if(!empty($input['password']))
                $data['password'] = Hash::make($input['password']);

            $user = User::where('id', $id)->update($data);

            if($user)
                $user = User::where('id', $id)->first();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario actualizado con exito.'
            ], 200);

            

        } catch(QueryException $e){
            return response()->json([
                'success' => false,
                'message' => 'Error de inserción de datos sql.',
                'errors' => $e
            ], 400);
        }
    }

   
}
