<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    /**
     * Muestra una lista de todos los personajes favoritos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $listFavorites = Favorite::where('id_user', $user->id)->get()->toArray();
        return response()->json([
            'success' => true,
            'data' => $listFavorites,
            'message' => 'Lista de favorito.'
        ], 200);
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
        $input = $request->only(['ref_api']);

        $validate_data = [
            'ref_api' => 'required',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de parametros recibidos.',
                'errors' => $validator->errors()
            ]);
        }

        $user = auth()->user();

        
        $favorite = Favorite::where('id_user', $user->id)->where('ref_api', $input['ref_api'])->first();
        
        
        if($favorite !== null){
            $favorite->delete();

            return response()->json([
                'success' => true,
                'data' => $favorite,
                'message' => 'Elemento eliminado de favorito con exito.'
            ], 200);
        } 

        $favorite = new Favorite();
        $favorite->id_user = $user->id;
        $favorite->ref_api = $input['ref_api'];

        if($favorite->save()){
            return response()->json([
                'success' => true,
                'data' => $favorite,
                'message' => 'Elemento agregado a favorito con exito.'
            ], 200);
        } 

        

        return response()->json([
            'success' => false,
            'message' => 'Elemento no guardado a favorito.'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
