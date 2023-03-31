<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Disk;
use App\Models\Server;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = Server::with('disks')->get();

        return response()->json($servers);
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

        Log::channel('laravel')->info(["Request" => $request->all()]);
        $serverData = $request->validate(Server::$rules);
        $diskData = $request->validate([
            'disks' => 'required|array|min:1',
            'disks.*.capacity' => 'required|numeric',
            'disks.*.used' => 'required|numeric',
            'disks.*.mounted' => 'required|string',
        ]);

        $server = Server::where('nit', $serverData['nit'])
            ->first();

        if (!$server) {
            $server = new Server;
            $server->nombre = $serverData['nombre'];
            $server->nit = $serverData['nit'];
            $clientes = Cliente::where('nit', $server->nit)
                ->get();
            foreach ($clientes as $cliente) {
                $server->cliente_id = $cliente->id;
            }
        }

        $server->disk_capacidad = $serverData['disk_capacidad'];
        $server->ram = $serverData['ram'];
        $server->save();


        foreach ($diskData['disks'] as $diskDataItem) {
            $disk = Disk::where('server_id', $server->id)
                ->where('capacity', $diskDataItem['capacity'])
                ->first();

            if (!$disk) {
                $disk = new Disk;
                $disk->server_id = $server->id;
                $disk->capacity = $diskDataItem['capacity'];
            }


            $disk->mounted = $diskDataItem['mounted'];
            $disk->used = $diskDataItem['used'];
            $disk->save();
        }

        $server = Server::with('disks')->findOrFail($server->id);
        Log::channel('laravel')->info($server);
        return response()->json($server);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        $server = Server::with('disks')->findOrFail($server->id);
        
        return response()->json($server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
