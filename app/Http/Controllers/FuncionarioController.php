<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funcionarios = Funcionario::all();
        return view('funcionarios.index', compact('funcionarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('funcionarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Funcionario::$rules);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->nombre,
                'email' => $request->correo,
                'identificacion' => $request->identificacion,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
                'password' => bcrypt($request->identificacion)
            ]);

            $user->assignRole('cliente');

            Funcionario::create([
                'cargo' => $request->cargo,
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('funcionarios.index')->with('success', 'Cliente creado correctamente.');
        } catch (Exception $e) {
            Log::alert("Error al crear funcionario", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function show(Funcionario $funcionario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function edit(Funcionario $funcionario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Funcionario $funcionario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Funcionario  $funcionario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funcionario $funcionario)
    {
        //
    }
}
