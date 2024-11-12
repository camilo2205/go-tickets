<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fechas = explode(' - ', $request->fecha);
        $cliente_id = $request->cliente_id;
        $funcionario_id = $request->funcionario_id;
        $proyectos = DB::table('bitacora')->select('proyecto')->distinct()->get();
        $proyecto_filter = $request->proyecto_filter ? $request->proyecto_filter : [];
        $estado = $request->estado;
        $consulta = Bitacora::query();

        if (isset($fechas[1])) {
            $consulta->whereDate('created_at', '>=', $fechas[0])
                ->whereDate('created_at', '<=', $fechas[1]);
        }
        if ($cliente_id) {
            $consulta->where('cliente_id', $cliente_id);
        }

        $bitacora = $consulta->orderBy('id', 'desc')->paginate(10);
        $clientes = Cliente::all();
        $funcionarios = Funcionario::all();

        return view('bitacora.index', compact(
            'bitacora',
            'clientes',
            'funcionarios',
            'fechas',
            'cliente_id',
            'funcionario_id',
            'estado',
            'proyectos',
            'proyecto_filter')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        $clientes = Cliente::all();
        $proyectos = DB::table('bitacora')->select('proyecto')->distinct()->get();
        if ($funcionario && !auth()->user()->hasRole('superadmin')) {
            $funcionarios = Funcionario::where('user_id', auth()->user()->id)->get();
        } else {
            $funcionarios = Funcionario::all();
        }
        return view('bitacora.create', compact('clientes', 'funcionarios', 'funcionario', 'cliente', 'proyectos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Bitacora::$rules);
        $inicio_fin = explode(' - ', $request->inicio_fin);

        $bitacora_entry = new Bitacora();
        $bitacora_entry->nombre = $request->nombre;
        $bitacora_entry->proyecto = $request->proyecto;
        $bitacora_entry->cliente_id = $request->cliente_id;
        $bitacora_entry->funcionario_id = $request->funcionario_id;
        $bitacora_entry->inicio = $inicio_fin[0];
        $bitacora_entry->fin = $inicio_fin[1];
        $bitacora_entry->esfuerzo = $request->esfuerzo;
        $bitacora_entry->descripcion = $request->descripcion;
        
        return redirect()->route('bitacora.index')->with('success', 'Entrada creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bitacora  $bitacora
     * @return \Illuminate\Http\Response
     */
    public function show(Bitacora $bitacora)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bitacora  $bitacora
     * @return \Illuminate\Http\Response
     */
    public function edit(Bitacora $bitacora)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bitacora  $bitacora
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bitacora $bitacora)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bitacora  $bitacora
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bitacora $bitacora)
    {
        //
    }
}
