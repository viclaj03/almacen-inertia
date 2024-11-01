<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeloController extends Controller
{
    


    public function addFavorite(Request $request){

        $modelo = Modelo::findOrFail($request->id);

        if($modelo->isFavoritedByUser()){
            //dd($image->favoritedBy->where('user_id',Auth::user()->id));
            $modelo->favoritedBy()->detach(Auth::user()->id);
        } else {
            $modelo->favoritedBy()->attach(Auth::user()->id,['comment'=>"revisar","last_change_date"=>Carbon::now()]);
        }
        
    }



}
