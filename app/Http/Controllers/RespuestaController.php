<?php

namespace App\Http\Controllers;

use App\Events\MessageUpdate;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Respuesta;
use App\Notifications\TicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'cuerpo' => 'required|string',
            'file_message.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,pdf,doc,docx,txt,zip|max:8192', // max:8192 es 8MB
        ]);


        $respuestaData = $request->only(['cuerpo', 'user_id', 'ticket_id', 'cerrar', 'notificado']);
        $files = [];

        // Manejar la carga de archivos
        if ($request->hasFile('file_message')) {
            foreach ($request->file('file_message') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('messages_file', $filename);

                $files[] = [
                    'file_name' => $filename,
                    'file_path' => $path,
                ];
            }
        }

        $respuestaData['files'] = json_encode($files);
        // Crear la respuesta
        $respuesta = Respuesta::create($respuestaData);

        $cliente = Cliente::where('user_id', auth()->user()->id)->first();
        $funcionario = Funcionario::where('user_id', auth()->user()->id)->first();
        $ticket = $respuesta->ticket;
        if ($request->cerrar == 0) {
            if (!$cliente) {
                $ticket->estado = 'atendido';
                sendNotification($ticket->cliente->user, "Su ticket número " . $ticket->id . " ha sido respondido", "/tickets/{$ticket->id}", $ticket->id);
                sendSMS($ticket->cliente->user->telefono, "Su ticket número " . $ticket->id . " ha sido respondido:\n \"" . $respuesta->cuerpo . "\"");
                if (!$funcionario) {
                    sendNotification($ticket->funcionario->user, "Su ticket número " . $ticket->id . " ha sido respondido", "/tickets/{$ticket->id}", $ticket->id);
                    sendSMS($ticket->funcionario->user->telefono, "Su ticket número " . $ticket->id . " ha sido respondido:\n \"" . $respuesta->cuerpo . "\"");
                }
            } else {
                sendNotification($ticket->funcionario->user, "Su ticket número " . $ticket->id . " ha sido respondido", "/tickets/{$ticket->id}", $ticket->id);
                sendSMS($ticket->funcionario->user->telefono, "Su ticket número " . $ticket->id . " ha sido respondido:\n \"" . $respuesta->cuerpo . "\"");
            }
        } else {
            $ticket->cerrado_por = auth()->user()->id;
            $ticket->estado = 'resuelto';
            sendNotification($ticket->funcionario->user, "Su ticket número " . $ticket->id . " ha sido cerrado", "/tickets/{$ticket->id}", $ticket->id);
            sendSMS($ticket->funcionario->user->telefono, "Su ticket número " . $ticket->id . " ha sido cerrado:\n \"" . $respuesta->cuerpo . "\"");
        }
        $ticket->save();
        
        if ($request->cerrar) {
            return redirect()->back();
        }
          // event(new InformeUpdated('ss'));
          broadcast(new MessageUpdate($respuesta))->toOthers();

        return response()->json(['status' => "success", 'respuesta' => $respuesta, 'ticket' => $ticket]);
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
