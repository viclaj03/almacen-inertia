<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function __construct()
     {
        // $this->middleware('auth',['only' => 'index']);

        $this->middleware(function ($request, $next) {
            // Verifica si el usuario está autenticado y su ID es igual a 1
            if (!auth()->check() || auth()->user()->id !== 1) {
                // Si no es el usuario con ID 1, redirige o devuelve una respuesta no autorizada
                return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
            }
    
            return $next($request);
        });
     }


    public function index()
    {

        $users = User::paginate(10)->onEachSide(1);
        return Inertia::render('User',compact('users',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        dd($user);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function changePegui(){
        $user = User::where('id',Auth::id())->first();
        if($user->id != 1){
            return;
        }

        $user->pegi_18 = !$user->pegi_18;
        $user->save();
    }
}
