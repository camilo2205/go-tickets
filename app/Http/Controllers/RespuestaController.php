<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Respuesta;
use Illuminate\Http\Request;

class RespuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'cuerpo' => 'required'
        ]);
        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $respuesta = Respuesta::create($request->all());
        $ticket = $respuesta->ticket;
        if ($request->cerrar == 0) {
            if (!$cliente) {
                $ticket->estado = 'atendido';
                sendSMS($ticket->cliente->user->telefono, "Su ticket número ".$ticket->id." ha sido respondido:\n \"".$respuesta->cuerpo."\"");
            } else {
                sendSMS($ticket->funcionario->user->telefono, "Su ticket número ".$ticket->id." ha sido respondido:\n \"".$respuesta->cuerpo."\"");
            }
        } else {
            $ticket->cerrado_por = auth()->user()->id;
            $ticket->estado = 'resuelto';
            sendSMS($ticket->funcionario->user->telefono, "Su ticket número ".$ticket->id." ha sido cerrado:\n \"".$respuesta->cuerpo."\"");
        }
        $ticket->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function show(Respuesta $respuesta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function edit(Respuesta $respuesta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Respuesta $respuesta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Respuesta $respuesta)
    {
        //
    }
}
