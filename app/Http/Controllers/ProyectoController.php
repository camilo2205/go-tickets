<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $cliente_id = $request->cliente_id;
        $clientes = Cliente::all();
        $consulta = Proyecto::query();
        
        $proyectos = $consulta->orderBy('id', 'desc')->paginate(10);
        return view('proyectos.index', compact('proyectos', 'clientes', 'cliente_id'));
    }

    public function create() {
        $clientes = Cliente::all();
        return view('proyectos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate(Proyecto::$rules);
        $proyecto = $request->except('_token');
        $proyecto['user_id'] = auth()->user()->id;
        Proyecto::create($proyecto);
        return redirect()->route('proyectos.index')->with('success', 'Entrada creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function show(Proyecto $proyecto)
    {
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Proyecto $proyecto)
    {
        $clientes = Cliente::all();
        return view('proyectos.edit', compact('proyecto', 'clientes'));   
    }

    function update(Proyecto $proyecto, Request $request) {
        $request->validate(Proyecto::$rules);
        $proyecto->update($request->all());
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Proyecto $proyecto)
    {
        try {
            DB::beginTransaction();
            $proyecto->delete();
            DB::commit();
            return redirect()->back()->with("success", 'Proyecto eliminado correctamente');
        } catch (\Exception $e) {
            Log::alert("Error al eliminar proyecto", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }
}
