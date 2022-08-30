<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index() {
        return view('auth.register');
    }

    public function store(Request $request) {

        //Obtener un valor de un formulario
        //dd($request->get('username'));

        //Modificar request Para evitar el mensaje de error al crear un username duplicado
        $request->request->add(['username' => Str::slug( $request->username )]);

        //Validación
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make( $request->password )
        ]);

        //Autenticar usuario forma1:
        // auth()->attempt([
        //     'email' => $request->email,
        //     'password' => $request->password
        // ]);

        //Forma2:
        auth()->attempt($request->only('email', 'password'));


        //Redireccionar usuario
        return redirect()->route('posts.index', auth()->user()->username);

    }
}
