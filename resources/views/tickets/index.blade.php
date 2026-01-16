<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/tickets.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="grid grid-cols-3 mb-2">
            <div class="col-start-2">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('TICKETS') }}
                </h2>
            </div>
            <div>
                <x-anchor href="{{ route('tickets:reporte', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'estado'=> $estado, 'fecha'=> isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '']) }}" target="_blank">
                    Reporte &nbsp;
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </x-anchor>
            </div>
        </div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <x-anchor href="{{ route('tickets.create') }}">
                    <i class="fas fa-plus mr-2"></i>Crear Ticket
                </x-anchor>
            </div>
            <form action="" id="filtrar" class="flex items-end gap-4 flex-1 px-6">
                <input type="hidden" id="estado" name="estado" value="{{ $estado }}">
                <div class="flex-1">
                    <x-label for="buscar" class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-search mr-1"></i>Buscar
                    </x-label>
                    <x-input type="text" id="buscar" class="w-full filtro" name="buscar" 
                        placeholder="Buscar por título, descripción o #ticket..." 
                        value="{{ $buscar }}" />
                </div>
                <div class="w-64">
                    <x-label for="fecha" class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i>Fecha
                    </x-label>
                    <x-input type="text" id="fecha" class="w-full filtro" name="fecha"
                        value='{{ isset($fechas[1]) ? "$fechas[0] - $fechas[1]" : "" }}' 
                        placeholder="Seleccionar rango..." 
                        autocomplete='off' />
                </div>
                <div class="w-72">
                    <x-label for="tags_id" class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-tag mr-1"></i>Tag
                    </x-label>
                    <x-select name='tags_id' class="filtro tags form-control w-full">
                        <option value="">Todos los tags</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ (is_array($tags_id) ? in_array($tag->id, $tags_id) : $tags_id == $tag->id) ? 'selected' : '' }}>{{ $tag->nombre }}</option>
                        @endforeach
                    </x-select>
                </div>
            </form>
            <div>
                @if (session('success'))
                    <div>
                        <x-small-message class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                            {{ session('success') }}
                        </x-small-message>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="bg-blue-50 p-4 rounded-lg shadow-sm border border-blue-200">
        <div class="flex items-center justify-between">
            <div class="text-blue-900 font-medium flex items-center">
                <i class="fas fa-ticket-alt mr-2"></i>
                Mostrando
                <span class="mx-1 px-2 py-1 bg-blue-100 rounded font-semibold">{{ $tickets->firstItem() }}</span>
                a
                <span class="mx-1 px-2 py-1 bg-blue-100 rounded font-semibold">{{ $tickets->lastItem() }}</span>
                de
                <span class="mx-1 px-2 py-1 bg-blue-100 rounded font-semibold">{{ $tickets->total() }}</span>
                tickets
                @if ($tickets->onFirstPage())
                    <span class="text-gray-400 mx-2">Anterior</span>
                @else
                    <a href="{{ $tickets->appends(['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'estado' => $estado, 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : ''])->previousPageUrl() }}" class="text-blue-700 underline mx-2 hover:text-blue-900">Anterior</a>
                @endif
                @if ($tickets->hasMorePages())
                    <a href="{{ $tickets->appends(['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'estado' => $estado, 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : ''])->nextPageUrl() }}" class="text-blue-700 underline mx-2 hover:text-blue-900">Siguiente</a>
                @else
                    <span class="text-gray-400 mx-2">Siguiente</span>
                @endif
            </div>
            <div class="flex items-center justify-end">
                <div class="flex space-x-2 ml-auto pl-6">
                    <a href="{{ route('tickets.index', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '']) }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ !$estado ? 'bg-gray-600 text-white border-2 border-gray-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fas fa-circle mr-1"></i> Todos
                    </a>
                    <a href="{{ route('tickets.index', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'creado'])}}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estado == 'creado' ? 'bg-blue-600 text-white border-2 border-blue-800' : 'bg-blue-100 text-blue-800' }}">
                        <i class="fas fa-circle mr-1"></i> Abierto
                    </a>
                    <a href="{{ route('tickets.index', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'asignado'])}}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estado == 'asignado' ? 'bg-yellow-600 text-white border-2 border-yellow-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <i class="fas fa-user-check mr-1"></i> Asignado
                    </a>
                    <a href="{{ route('tickets.index', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'atendido'])}}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estado == 'atendido' ? 'bg-purple-600 text-white border-2 border-purple-800' : 'bg-purple-100 text-purple-800' }}">
                        <i class="fas fa-headset mr-1"></i> Atendido
                    </a>
                    <a href="{{ route('tickets.index', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'resuelto'])}}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estado == 'resuelto' ? 'bg-green-600 text-white border-2 border-green-800' : 'bg-green-100 text-green-800' }}">
                        <i class="fas fa-check mr-1"></i> Resuelto
                    </a>
                    <a href="{{ route('tickets.index', ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'cerrado'])}}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estado == 'cerrado' ? 'bg-gray-600 text-white border-2 border-gray-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fas fa-lock mr-1"></i> Cerrado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <table class="border-collapse border-2 border-gray-500 w-full rounded-lg overflow-hidden shadow-sm">
        <thead class="bg-blue-50">
            <tr class="border-2 border-gray-500">
                <th class="border border-gray-200 px-5 py-1 w-1/6 text-blue-900 font-semibold border-b-2 border-blue-200">CLIENTE</th>
                <th class="border border-gray-200 px-5 py-1 w-1/4 text-blue-900 font-semibold border-b-2 border-blue-200">TICKET</th>
                <th class="border border-gray-200 px-5 py-1 w-1/4 text-blue-900 font-semibold border-b-2 border-blue-200">CONVERSACIÓN</th>
                <th class="border border-gray-200 px-5 py-1 w-1/6 text-blue-900 font-semibold border-b-2 border-blue-200">FECHA / HORA</th>
                <th class="border border-gray-200 px-5 py-1 w-1/6 text-blue-900 font-semibold border-b-2 border-blue-200">TRANSCURRIDO</th>
                <th class="border border-gray-200 px-5 py-1 w-1/6 text-blue-900 font-semibold border-b-2 border-blue-200">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr class="border-2 border-gray-200">
                    <td class="border border-gray-200 px-2 py-2 text-gray-700">
                        <span class="text-xs font-bold">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span><br>
                        {{ Str::limit($ticket->cliente->razon_social, 15, '...') }}
                    </td>
                    <td class="border border-gray-200 px-2 py-1 text-gray-700">
                        <span class="font-bold">{{ Str::limit($ticket->titulo ?? "Aquí va el título", 20, '...') }}</span><br>
                        <span class="text-xs">{{ Str::limit($ticket->descripcion, 60, '...') }}</span>
                    </td>
                    <td class="border border-gray-200 px-2 py-1 text-gray-700">
                        @foreach ($ticket->respuestas->take(3) as $respuesta)
                            <span class="text-xs font-bold">{{ $respuesta->user->name }}:</span>
                            <span class="text-xs">{{ Str::limit($respuesta->cuerpo, 40, '...') }}</span><br>
                        @endforeach
                    </td>
                    <td class="border border-gray-200 px-2 py-1 text-gray-700">
                        <i class="fas fa-calendar-alt mr-1"></i>{{ formatDate($ticket->created_at, "d/m/Y") }}<br>
                        <i class="fas fa-clock mr-1"></i>{{ formatDate($ticket->created_at, "H:i:s") }}
                    </td>
                    <td class="border border-gray-200 px-2 py-1 text-gray-700">
                        <i class="fas fa-hourglass-half mr-1"></i>{{ $ticket->created_at->diffForHumans($ticket->corregido_at) }}<br>
                        @switch($ticket->sla)
                            @case(1)
                                @if ($ticket->created_at->diffInHours($ticket->corregido_at) <= 1)
                                    <span class="text-sm text-green-600 font-bold"><i class="fas fa-check-circle mr-1"></i>SLA OK</span>
                                @elseif ($ticket->created_at->diffInHours($ticket->corregido_at) <= 2)
                                    <span class="text-sm text-yellow-600 font-bold"><i class="fas fa-exclamation-circle mr-1"></i>SLA en riesgo</span>
                                @else
                                    <span class="text-sm text-red-600 font-bold"><i class="fas fa-times-circle mr-1"></i>SLA Incumplido</span>
                                @endif
                                @break
                            @case(2)
                                @if ($ticket->created_at->diffInHours($ticket->corregido_at) <= 4)
                                    <span class="text-sm text-green-600 font-bold"><i class="fas fa-check-circle mr-1"></i>SLA OK</span>
                                @elseif ($ticket->created_at->diffInHours($ticket->corregido_at) <= 8)
                                    <span class="text-sm text-yellow-600 font-bold"><i class="fas fa-exclamation-circle mr-1"></i>SLA en riesgo</span>
                                @else
                                    <span class="text-sm text-red-600 font-bold"><i class="fas fa-times-circle mr-1"></i>SLA Incumplido</span>
                                @endif
                                @break
                            @default
                                @if ($ticket->created_at->diffInDays($ticket->corregido_at) <= 1)
                                    <span class="text-sm text-green-600 font-bold"><i class="fas fa-check-circle mr-1"></i>SLA OK</span>
                                @elseif ($ticket->created_at->diffInHours($ticket->corregido_at) <= 2)
                                    <span class="text-sm text-yellow-600 font-bold"><i class="fas fa-exclamation-circle mr-1"></i>SLA en riesgo</span>
                                @else
                                    <span class="text-sm text-red-600 font-bold"><i class="fas fa-times-circle mr-1"></i>SLA Incumplido</span>
                                @endif
                        @endswitch
                    </td>
                    <td class="border border-gray-200 px-2 py-1 text-gray-700">
                        @switch($ticket->estado)
                            @case('creado')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-circle mr-1"></i>
                                    Abierto
                                </span>
                                @break
                            @case('asignado')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-user-check mr-1"></i>
                                    Asignado
                                </span>
                                @break
                            @case('atendido')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-headset mr-1"></i>
                                    Atendido
                                </span>
                                @break
                            @case('resuelto')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Corregido
                                </span>
                                @break
                            @case('cerrado')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-lock mr-1"></i>
                                    Cerrado
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-question mr-1"></i>
                                    {{ ucfirst($ticket->estado) }}
                                </span>
                        @endswitch
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window._env = {
            PUSHER_APP_ID: '{{ config('app.pusher_app_id') }}',
            PUSHER_APP_KEY: '{{ config('app.pusher_app_key') }}',
            PUSHER_APP_CLUSTER: '{{ config('app.pusher_app_cluster') }}',
            WS_HOST: '{{ config('app.ws_host') }}',
            WS_PORT: '{{ config('app.ws_port') }}',
        };
    </script>
    
    <script src="{{ asset('js/cruds/tickets.js?id=03') }}" defer></script>
</x-app-layout>
