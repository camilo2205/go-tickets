<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Soporte;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        if ($cliente) {
            $tickets = Ticket::where('cliente_id', $cliente->id)->get();
        } elseif ($funcionario) {
            $tickets = Ticket::where('funcionario_id', $funcionario->id)
            ->orWhereNull('funcionario_id')
            ->get();
        } else {
            $tickets = Ticket::all();
        }
        return view('tickets.index', compact('tickets', 'cliente', 'funcionario'));
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
}
