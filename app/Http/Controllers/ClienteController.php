<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:clientes.index')->only('index');
        $this->middleware('can:clientes.create')->only(['create', 'store']);
        $this->middleware('can:clientes.edit')->only(['edit', 'update']);
        $this->middleware('can:clientes.show')->only('show');
        $this->middleware('can:clientes.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Cliente::$rules);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->nombre_encargado,
                'email' => $request->correo,
                'identificacion' => $request->identificacion_encargado,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
                'password' => bcrypt($request->identificacion_encargado)
            ]);

            $user->assignRole('cliente');

            Cliente::create([
                'nit' => $request->nit,
                'razon_social' => $request->nombre,
                'identificacion_encargado' => $request->identificacion_encargado,
                'nombre_encargado' => $request->nombre_encargado,
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
        } catch (Exception $e) {
            Log::alert("Error al crear cliente", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        dd('¿Aquí?');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nit' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'celular' => 'required',
            'correo' => [
                'required',
                Rule::unique('users','email')->ignore($cliente->user->id)
            ]
        ]);

        try {
            DB::beginTransaction();

            $user = $cliente->user;

            $user->update([
                'name' => $request->nombre_encargado,
                'email' => $request->correo,
                'identificacion' => $request->identificacion_encargado,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
            ]);

            $cliente->update([
                'nit' => $request->nit,
                'razon_social' => $request->nombre,
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
        } catch (Exception $e) {
            Log::alert("Error al actualizar cliente", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        try {
            DB::beginTransaction();
            $user = $cliente->user;
            $user->delete();
            $cliente->delete();
            DB::commit();
            return redirect()->back()->with("success", 'Cliente eliminado correctamente');
        } catch (\Exception $e) {
            Log::alert("Error al eliminar cliente", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }
}
