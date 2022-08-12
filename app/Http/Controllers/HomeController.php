<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }

    public function estadisticas()
    {
        $ticketsArray = [];
        $ticketsAsignadosArray = [];
        $ticketsSinAsignarArray = [];
        for ($i = 0; $i < 12; $i++) {
            $cliente = Cliente::where('user_id', auth()->user()->id)->first();
            $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
            if ($cliente) {
                $tickets = Ticket::where('cliente_id', $cliente->id)->whereYear("created_at", Carbon::now())
                    ->whereMonth('created_at', $i)
                    ->count();
                $ticketsResueltos = Ticket::where('cliente_id', $cliente->id)->whereYear("created_at", Carbon::now())
                    ->whereMonth('created_at', $i)
                    ->where('estado', 'resuelto')
                    ->count();
            } elseif ($funcionario) {
                $tickets = Ticket::where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                    })
                    ->whereYear("created_at", Carbon::now())
                    ->whereMonth('created_at', $i)
                    ->count();
                $ticketsResueltos = Ticket::where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                    })
                    ->whereYear("created_at", Carbon::now())
                    ->whereMonth('created_at', $i)
                    ->where('estado', 'resuelto')
                    ->count();
            } else {
                $tickets = Ticket::whereYear("created_at", Carbon::now())
                    ->whereMonth('created_at', $i)
                    ->count();
                $ticketsResueltos = Ticket::whereYear("created_at", Carbon::now())
                    ->whereMonth('created_at', $i)
                    ->where('estado', 'resuelto')
                    ->count();
            }
            $ticketsArray[$i] = $tickets;
            $ticketsResueltosArray[$i] = $ticketsResueltos;
        }

        if ($cliente) {
            $cantidadTicketsAsignados = Ticket::where('estado', 'asignado')
                ->where('cliente_id', $cliente->id)
                ->count();
            $cantidadTicketsSinAsignar = Ticket::where('estado', 'creado')
                ->where('cliente_id', $cliente->id)
                ->count();
            $cantidadTicketsAtendidos = Ticket::where('estado', 'atendido')
                ->where('cliente_id', $cliente->id)
                ->count();
            $cantidadTicketsResueltos = Ticket::where('estado', 'resuelto')
                ->where('cliente_id', $cliente->id)
                ->count();
            $dataPie = [$cantidadTicketsSinAsignar, $cantidadTicketsAsignados, $cantidadTicketsAtendidos, $cantidadTicketsResueltos];
        } elseif ($funcionario) {
            $cantidadTicketsAsignados = Ticket::where('estado', 'asignado')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->count();
            $cantidadTicketsSinAsignar = Ticket::where('estado', 'creado')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->count();
            $cantidadTicketsAtendidos = Ticket::where('estado', 'atendido')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->count();
            $cantidadTicketsResueltos = Ticket::where('estado', 'resuelto')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->count();
            $dataPie = [$cantidadTicketsSinAsignar, $cantidadTicketsAsignados, $cantidadTicketsAtendidos, $cantidadTicketsResueltos];
        } else {
            $cantidadTicketsAsignados = Ticket::where('estado', 'asignado')->count();
            $cantidadTicketsSinAsignar = Ticket::where('estado', 'creado')->count();
            $cantidadTicketsAtendidos = Ticket::where('estado', 'atendido')->count();
            $cantidadTicketsResueltos = Ticket::where('estado', 'resuelto')->count();
            $dataPie = [$cantidadTicketsSinAsignar, $cantidadTicketsAsignados, $cantidadTicketsAtendidos, $cantidadTicketsResueltos];
        }
        return response()->json(compact('ticketsArray', 'ticketsResueltosArray', 'dataPie'));
    }
}
