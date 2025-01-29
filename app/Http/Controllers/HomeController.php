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
        $meses = [];
        $hoy = Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $mes = $hoy->month - $i <= 0 ? ($hoy->month - $i + 12) : $hoy->month - $i;
            $year = $hoy->month - $i <= 0 ? Carbon::now()->subYear() : Carbon::now();
            $meses[] = $mes;
            $cliente = Cliente::where('user_id', auth()->user()->id)->first();
            $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
            if ($cliente) {
                $tickets = Ticket::where('cliente_id', $cliente->id)->whereYear("created_at", $year)
                    ->whereMonth('created_at', $mes)
                    ->count();
                $ticketsResueltos = Ticket::where('cliente_id', $cliente->id)->whereYear("created_at", $year)
                    ->whereMonth('created_at', $mes)
                    ->where('estado', 'resuelto')
                    ->count();
            } elseif ($funcionario) {
                $tickets = Ticket::where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                    ->whereYear("created_at", $year)
                    ->whereMonth('created_at', $mes)
                    ->count();
                $ticketsResueltos = Ticket::where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                    ->whereYear("created_at", $year)
                    ->whereMonth('created_at', $mes)
                    ->where('estado', 'resuelto')
                    ->count();
            } else {
                $tickets = Ticket::whereYear("created_at", $year)
                    ->whereMonth('created_at', $mes)
                    ->count();
                $ticketsResueltos = Ticket::whereYear("created_at", $year)
                    ->whereMonth('created_at', $mes)
                    ->where('estado', 'resuelto')
                    ->count();
            }
            $ticketsArray[] = $tickets;
            $ticketsResueltosArray[] = $ticketsResueltos;
        }

        if ($cliente) {
            $cantidadTicketsAsignados = Ticket::where('estado', 'asignado')
                ->where('cliente_id', $cliente->id)
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsSinAsignar = Ticket::where('estado', 'creado')
                ->where('cliente_id', $cliente->id)
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsAtendidos = Ticket::where('estado', 'atendido')
                ->where('cliente_id', $cliente->id)
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsResueltos = Ticket::where('estado', 'resuelto')
                ->where('cliente_id', $cliente->id)
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $dataPie = [$cantidadTicketsSinAsignar, $cantidadTicketsAsignados, $cantidadTicketsAtendidos, $cantidadTicketsResueltos];
        } elseif ($funcionario) {
            $cantidadTicketsAsignados = Ticket::where('estado', 'asignado')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsSinAsignar = Ticket::where('estado', 'creado')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsAtendidos = Ticket::where('estado', 'atendido')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsResueltos = Ticket::where('estado', 'resuelto')
                ->where(function ($query) use ($funcionario) {
                    $query->where('funcionario_id', $funcionario->id)
                        ->orWhereNull('funcionario_id');
                })
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $dataPie = [$cantidadTicketsSinAsignar, $cantidadTicketsAsignados, $cantidadTicketsAtendidos, $cantidadTicketsResueltos];
        } else {
            $cantidadTicketsAsignados = Ticket::where('estado', 'asignado')
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsSinAsignar = Ticket::where('estado', 'creado')
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsAtendidos = Ticket::where('estado', 'atendido')
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $cantidadTicketsResueltos = Ticket::where('estado', 'resuelto')
                ->whereMonth('created_at', Carbon::now())
                ->count();
            $dataPie = [$cantidadTicketsSinAsignar, $cantidadTicketsAsignados, $cantidadTicketsAtendidos, $cantidadTicketsResueltos];
        }

        $clientesConTickets = Cliente::withCount('tickets')
        ->orderBy('tickets_count', 'desc')
        ->get();

        return response()->json(compact('ticketsArray', 'ticketsResueltosArray', 'dataPie', 'meses', 'clientesConTickets'));
    }
}
