<?php

namespace App\Http\Controllers;

use App\Events\TareaUpdated;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fullscreen = $request->query('fullscreen', false);
        $encargados = Funcionario::all();
        $tareas = Tarea::orderByRaw('ISNULL(enfoque), enfoque ASC, prioridad desc, estado')
        ->get();
        if ($fullscreen) {
            return view('tareas.index', compact('tareas', 'encargados', 'fullscreen'));
        } else {
            return view('tareas.index', compact('tareas', 'encargados'));
        }
    }

    public function render(Request $request)
    {
        // dd(Tarea::orderByRaw('ISNULL(enfoque), enfoque ASC, prioridad desc, estado')->toSql());
        $tareas = Tarea::orderByRaw('ISNULL(enfoque), enfoque ASC, prioridad desc, estado')
        ->get();
        $encargados = Funcionario::all();
        $fullscreen = $request->query('fullscreen', false);
        if ($fullscreen) {
            return view('tareas.partials.tbody', compact('tareas', 'encargados', 'fullscreen'));
        } 
        return view('tareas.partials.tbody', compact('tareas', 'encargados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $encargados = Funcionario::all();
        $clientes = Cliente::all();
        return view('tareas.create', compact('encargados', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta',
            'encargado_id' => 'required|exists:users,id',
            'cliente_id' => 'required|exists:clientes,id',
        ]);
        try {
            Tarea::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'prioridad' => $request->prioridad,
                'encargado_id' => $request->encargado_id,
                'cliente_id' => $request->cliente_id,
            ]);

            broadcast(new TareaUpdated())->toOthers();
            return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear la tarea: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al crear la tarea: ' . $e->getMessage()]);
        }
    }

    public function show(Tarea $tarea)
    {
        return view('tareas.show', compact('tarea'));
    }

    public function destroy(Tarea $tarea)
    {
        try {
            $tarea->delete();
            return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar la tarea: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al eliminar la tarea: ' . $e->getMessage()]);
        }
    }
    /**
     * Actualiza la prioridad de una tarea.
     */
    public function updatePrioridad(Request $request, Tarea $tarea)
    {
        $request->validate([
            'prioridad' => 'nullable|in:baja,media,alta',
        ]);
        try {
            $tarea->prioridad = $request->prioridad;
            $tarea->save();
            broadcast(new TareaUpdated())->toOthers();
            return redirect()->route('tareas.index')->with('success', 'Prioridad actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar prioridad: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al actualizar prioridad: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualiza el enfoque de una tarea.
     */
    public function updateEnfoque(Request $request, Tarea $tarea)
    {
        $request->validate([
            'enfoque' => [
                'nullable',
                'integer',
                'min:1',
                'max:10',
                Rule::unique('tareas')->where(function ($query) use ($tarea) {
                    return $query->where('encargado_id', $tarea->encargado_id);
                })
            ]
        ],[
            'enfoque.required' => 'El campo enfoque es obligatorio.',
            'enfoque.integer' => 'El enfoque debe ser un número entero.',
            'enfoque.min' => 'El enfoque debe ser al menos 1.',
            'enfoque.max' => 'El enfoque no puede ser mayor a 10.',
            'enfoque.unique' => 'El encargado ya tiene una tarea con el mismo enfoque.'
        ]);
        try {
            $tarea->enfoque = $request->enfoque;
            $tarea->save();
            broadcast(new TareaUpdated())->toOthers();
            return redirect()->route('tareas.index')->with('success', 'Enfoque actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar enfoque: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al actualizar enfoque: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualiza el encargado de una tarea.
     */
    public function updateEncargado(Request $request, Tarea $tarea)
    {
        $request->validate([
            'encargado_id' => [
                'nullable',
                'exists:funcionarios,id',
                Rule::unique('tareas')->where(function ($query) use ($tarea) {
                    return $query->where('enfoque', $tarea->enfoque);
                })
            ]
        ],[
            'encargado_id.required' => 'El campo encargado es obligatorio.',
            'encargado_id.exists' => 'El encargado seleccionado no existe.',
            'encargado_id.unique' => 'El encargado ya tiene una tarea con el mismo enfoque.'
        ]);
        try {
            $tarea->encargado_id = $request->encargado_id;
            $tarea->save();
            broadcast(new TareaUpdated())->toOthers();
            return redirect()->route('tareas.index')->with('success', 'Encargado actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar encargado: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al actualizar encargado: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualiza el estado de una tarea.
     */
    public function updateEstado(Request $request, Tarea $tarea)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_progreso,completada,cancelada',
        ]);
        try {
            $tarea->estado = $request->estado;
            if ($tarea->estado === 'completada' || $tarea->estado === 'cancelada') {
                $tarea->enfoque = null;
            }
            if ($tarea->estado === 'cancelada') {
                $tarea->encargado_id = null; // Reset encargado_id when estado is updated
            }
            $tarea->save();
            broadcast(new TareaUpdated())->toOthers();
            return redirect()->route('tareas.index')->with('success', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al actualizar estado: ' . $e->getMessage()]);
        }
    }
}
