<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Response;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return $this->showAll($usuarios, Response::HTTP_OK);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $request->password = bcrypt($request->password);
        $request->verified = User::USUARIO_NO_VERIFICADO;
        $request->verification_token = User::generarVerificationToken();
        $request->admin = User::USUARIO_REGULAR;
        $user = User::create($request->all());

        return $this->showOne($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    
    {
        $usuario = User::findOrFail($id);

        return $this->showOne($usuario, Response::HTTP_OK);
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
        $rules = [
            'email' => 'email|unique:users,email,'.$id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_REGULAR. ',' . User::USUARIO_ADMINISTRADOR
        ];

        $this->validate($request, $rules);

        $user = User::findOrFail($id);

        if($request->has('name')) {
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email) {
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin') && $request->admin == User::USUARIO_ADMINISTRADOR) {
            if(!$user->esVerificado()) {
                return $this->errorResponse('El usuario no ha sido verificado!', Response::HTTP_CONFLICT);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()) {
            return $this->errorResponse('No se han enviado valores modificados!', Response::HTTP_NOT_MODIFIED);
        }
        
        $user->save();

        return $this->showOne($user, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $this->showOne($user, Response::HTTP_OK);
    }
}
