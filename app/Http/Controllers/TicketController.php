<?php

namespace App\Http\Controllers;

use App\Exports\TicketsExport;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Soporte;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Excel;

class TicketController extends Controller
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
        $estado = $request->estado;

        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        $consulta = Ticket::query();

        if (isset($fechas[1])) {
            $consulta->whereDate('created_at', '>=', $fechas[0])
                ->whereDate('created_at', '<=', $fechas[1]);
        }
        if ($estado) {
            $consulta->where('estado', $estado);
        }
        if ($cliente_id) {
            $consulta->where('cliente_id', $cliente_id);
        }

        if ($cliente) {
            $tickets = $consulta->where('cliente_id', $cliente->id)->orderBy('id', 'desc')->paginate(10);
            $clientes = [];
        } elseif ($funcionario) {
            $tickets = $consulta->where('funcionario_id', $funcionario->id)
                ->orWhereNull('funcionario_id')
                ->orderBy('id', 'desc')->paginate(10);
            $clientes = Cliente::all();
        } else {
            $tickets = $consulta->orderBy('id', 'desc')->paginate(10);
            $clientes = Cliente::all();
        }
        return view('tickets.index', compact('tickets', 'cliente', 'funcionario', 'clientes', 'fechas', 'cliente_id', 'estado'));
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
        if ($funcionario) {
            $funcionarios = Funcionario::where('user_id', auth()->user()->id)->get();
        } else {
            $funcionarios = Funcionario::all();
        }
        return view('tickets.create', compact('clientes', 'funcionarios', 'funcionario', 'cliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Ticket::$rules);

        try {
            DB::beginTransaction();
            $ticket = Ticket::create($request->all());
            if ($request->hasfile('soportes')) {
                foreach ($request->file('soportes') as $file) {
                    $path = $file->store('soportes');
                    $name = $file->getClientOriginalName();
                    Soporte::create(["nombre" => $name, "ruta" => $path, "ticket_id" => $ticket->id]);
                }
            }
            DB::commit();
            if ($request->funcionario_id) {
                sendToWhatsApp($ticket->funcionario->user->celular, "GoTelemedicina SAS te informa que se te ha asignado un nuevo ticket (Ticket #$ticket->id).");
            } else {
                foreach (Funcionario::all() as $funcionario) {
                    sendToWhatsApp($funcionario->user->celular, "GoTelemedicina SAS informa que un nuevo ticket ha sido creado (Ticket #$ticket->id).");
                }
            }
            return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente.');
        } catch (Exception $e) {
            Log::alert("Error al crear ticket", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        return view('tickets.show', compact('ticket', 'funcionario'));
    }

    public function notificar(Request $request)
    {
        $estado = "asignado";
        $notificar = Ticket::where('estado', $estado)->get();
        return response()->json(['notificar' => $notificar]);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        $funcionarios = Funcionario::all();
        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        if ($funcionario) {
            $funcionarios = Funcionario::where('user_id', auth()->user()->id)->get();
        } else {
            $funcionarios = Funcionario::all();
        }
        return view('tickets.edit', compact('ticket', 'funcionarios', 'cliente', 'funcionario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'descripcion' => 'required',
            'prioridad' => 'required',
            'tipo' => 'required'
        ]);
        $sendWhatpsApp = $request->funcionario_id != $ticket->funcionario_id;

        try {
            DB::beginTransaction();
            $ticket->update($request->all());
            if ($request->hasfile('soportes')) {
                foreach ($request->file('soportes') as $file) {
                    $path = $file->store('soportes');
                    $name = $file->getClientOriginalName();
                    Soporte::create(["nombre" => $name, "ruta" => $path, "ticket_id" => $ticket->id]);
                }
            }
            DB::commit();
            if ($sendWhatpsApp) {
                sendToWhatsApp($ticket->funcionario->user->celular, "GoTelemedicina SAS te informa que se te ha asignado un nuevo ticket (Ticket #$ticket->id).");
            }
            return redirect()->route('tickets.index')->with('success', 'Ticket actualizado.');
        } catch (Exception $e) {
            Log::alert("Error al actualizar ticket", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->route('tickets.index')->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        try {
            DB::beginTransaction();
            $ticket->delete();
            DB::commit();
            return redirect()->back()->with("success", 'Cliente eliminado correctamente');
        } catch (\Exception $e) {
            Log::alert("Error al eliminar cliente", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    public function getRespuestas(Ticket $ticket)
    {
        return response()->json(['respuestas' => $ticket->respuestas()->with(['user.cliente', 'user.funcionario'])->get()]);
    }

    public function reporte(Request $request)
    {
        $fechas = explode(' - ', $request->fecha);
        $cliente_id = $request->cliente_id;
        $estado = $request->estado;

        $razon_social = is_null($cliente_id) ? '' : " en " . Cliente::find($cliente_id)->razon_social;
        $_estado = is_null($estado) ? "" : " $estado" . "s";
        $_fechas = isset($fechas[1]) ? " entre $fechas[0] y $fechas[1]" : '';

        return Excel::download(new TicketsExport($cliente_id, $estado, $fechas), "Tickets$_estado$razon_social$_fechas.xlsx");
    }
}
