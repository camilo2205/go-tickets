<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/tickets.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between mb-4">
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

    <!-- Layout tipo cliente de correo -->
    <div class="flex gap-1">
        <!-- Barra lateral izquierda -->
        <div class="hidden lg:block w-56 border border-gray-200 p-3">
            <div class="space-y-2 mb-3 text-center">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('TICKETS') }}
                </h2>
                <span class="text-gray-600 text-sm" id="fecha-hora"></span>
            </div>
            <div class="space-y-1">
                <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => null])) }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ !$estado ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-inbox w-5 mr-3"></i>
                    <span class="flex-1">Todos</span>&nbsp;
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded-full">{{ $tickets->total() }}</span>
                </a>

                <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'creado']))}}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ $estado == 'creado' ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-envelope w-5 mr-3 text-blue-600"></i>
                    <span class="flex-1">Abiertos</span>
                </a>

                <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'asignado']))}}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ $estado == 'asignado' ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-user-check w-5 mr-3 text-yellow-600"></i>
                    <span class="flex-1">Asignados</span>
                </a>

                <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'atendido']))}}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ $estado == 'atendido' ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-headset w-5 mr-3 text-purple-600"></i>
                    <span class="flex-1">En atención</span>
                </a>

                <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'resuelto']))}}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ $estado == 'resuelto' ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-check-circle w-5 mr-3 text-green-600"></i>
                    <span class="flex-1">Resueltos</span>
                </a>

                <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'cerrado']))}}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ $estado == 'cerrado' ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-check-double w-5 mr-3 text-gray-600"></i>
                    <span class="flex-1">Cerrados</span>
                </a>

                <div class="border-t border-gray-200 my-3"></div>

                @if ($funcionario)
                <a href="{{ route('tickets.index', array_merge(request()->all(), ['mis_tickets' => request('mis_tickets') == '1' ? null : '1'])) }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request('mis_tickets') == '1' ? 'bg-purple-100 text-purple-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-user w-5 mr-3 text-purple-600"></i>
                    <span class="flex-1">Mis Tickets</span>
                </a>
                @endif

                <a href="{{ route('tickets.index', array_merge(request()->all(), ['urgente' => request('urgente') == '1' ? null : '1'])) }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request('urgente') == '1' ? 'bg-red-100 text-red-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-exclamation-triangle w-5 mr-3 text-red-600"></i>
                    <span class="flex-1">Urgentes</span>
                </a>

                @if ($funcionario)
                <a href="{{ route('tickets.index', array_merge(request()->all(), ['vit' => request('vit') == '1' ? null : '1'])) }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request('vit') == '1' ? 'bg-blue-100 text-blue-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-star w-5 mr-3 text-blue-600"></i>
                    <span class="flex-1">Importantes</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Área principal de mensajes -->
        <div class="flex-1 bg-white border border-gray-200 overflow-y-auto" style="max-height: calc(100vh - 160px);">
            <!-- Barra de herramientas superior móvil -->
            <div class="lg:hidden border-b border-gray-200 p-3">
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => null])) }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap {{ !$estado ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fas fa-inbox mr-1"></i> Todos
                    </a>
                    <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'creado']))}}"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap {{ $estado == 'creado' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800' }}">
                        <i class="fas fa-envelope mr-1"></i> Abierto
                    </a>
                    <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'asignado']))}}"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap {{ $estado == 'asignado' ? 'bg-yellow-600 text-white' : 'bg-yellow-50 text-yellow-800' }}">
                        <i class="fas fa-user-check mr-1"></i> Asignado
                    </a>
                    <a href="{{ route('tickets.index', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '', 'estado' => 'resuelto']))}}"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap {{ $estado == 'resuelto' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-800' }}">
                        <i class="fas fa-check mr-1"></i> Resuelto
                    </a>
                </div>
            </div>

            <!-- Lista de tickets estilo bandeja de entrada -->
            <div class="divide-y divide-gray-200">
                @foreach ($tickets as $ticket)
                    <div class="flex items-start p-4 gap-3">
                        @if ($funcionario)
                        <!-- Checkbox o avatar -->
                        <div class="flex-shrink-0 pt-1">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr($ticket->cliente->razon_social, 0, 2) }}
                            </div>
                        </div>
                        @endif

                        <!-- Contenido del ticket -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="{{ route('tickets.show', $ticket->id) }}">
                                        <span class="font-semibold text-gray-900 text-sm">
                                            {{ Str::limit($ticket->cliente->razon_social, 30, '...') }}
                                        </span>
                                        <span class="text-xs text-gray-500 font-mono">
                                                #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </a>
                                    @if ($ticket->prioridad == 'urgente')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Urgente
                                    </span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ formatDate($ticket->created_at, "d/m/Y H:i") }}
                                </span>
                            </div>

                            <div class="text-sm font-medium text-gray-900 mb-1">
                                {{ Str::limit($ticket->titulo ?? "Sin título", 100, '...') }}
                            </div>

                            <div class="text-xs text-gray-600 mb-2">
                                {{ Str::limit($ticket->descripcion, 250, '...') }}
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                @switch($ticket->estado)
                                @case('creado')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-circle mr-1 text-xs"></i> Abierto
                                </span>
                                @break
                                @case('asignado')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-user-check mr-1"></i> Asignado
                                </span>
                                @break
                                @case('atendido')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-headset mr-1"></i> Atendido
                                </span>
                                @break
                                @case('resuelto')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Resuelto
                                </span>
                                @break
                                @case('cerrado')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-archive mr-1"></i> Cerrado
                                </span>
                                @break
                                @endswitch

                                @if($ticket->nivel_sla)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $ticket->nivel_sla)) }}
                                </span>
                                @endif

                                @if($ticket->respuestas->count() > 0)
                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <i class="fas fa-comments mr-1"></i>
                                    {{ $ticket->respuestas->count() }} respuesta(s)
                                </span>
                                @endif

                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $ticket->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Árbol de respuestas expandible -->
                            @if($ticket->respuestas->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <button type="button"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 toggle-thread"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleThread({{ $ticket->id }})">
                                    Ver conversación ({{ $ticket->respuestas->count() }} mensajes)
                                </button>

                                <div id="thread-{{ $ticket->id }}" class="hidden mt-3 space-y-2 ml-2">
                                    @foreach ($ticket->respuestas->take(5) as $index => $respuesta)
                                    <div class="flex gap-2 text-xs">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ substr($respuesta->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-baseline gap-2 mb-0.5">
                                                <span class="font-semibold text-gray-800">{{ $respuesta->user->name
                                                    }}</span>
                                                <span class="text-gray-400">{{ $respuesta->created_at->diffForHumans()
                                                    }}</span>
                                            </div>
                                            <div
                                                class="text-gray-700 bg-gray-50 rounded-lg p-2 border-l-2 border-blue-300">
                                                {{ Str::limit($respuesta->cuerpo, 150, '...') }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <a href="{{ route('tickets.show', $ticket->id) }}">
                                        @if($ticket->respuestas->count() > 5)
                                        <div class="text-xs text-gray-500 italic ml-8">
                                            + {{ $ticket->respuestas->count() - 5 }} mensajes más...
                                        </div>
                                        @else
                                        <div class="text-xs text-gray-500 italic ml-8">
                                            ver todo...
                                        </div>
                                        @endif
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Indicador de estado SLA -->
                        <div class="flex-shrink-0 hidden lg:block">
                            @switch($ticket->sla)
                            @case(1)
                                @if ($ticket->created_at->diffInHours($ticket->corregido_at) <= 1)
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500" title="SLA OK"></span>
                                @elseif ($ticket->created_at->diffInHours($ticket->corregido_at) <= 2)
                                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-500" title="SLA en riesgo"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-500" title="SLA incumplido"></span>
                                @endif
                            @break
                            @case(2)
                                @if ($ticket->created_at->diffInHours($ticket->corregido_at) <= 4)
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500" title="SLA OK"></span>
                                @elseif ($ticket->created_at->diffInHours($ticket->corregido_at) <= 8)
                                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-500" title="SLA en riesgo"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-500" title="SLA incumplido"></span>
                                @endif
                            @break
                            @default
                                @if ($ticket->created_at->diffInDays($ticket->corregido_at) <= 1)
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500" title="SLA OK"></span>
                                @elseif ($ticket->created_at->diffInHours($ticket->corregido_at) <= 2)
                                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-500" title="SLA en riesgo"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-500" title="SLA incumplido"></span>
                                @endif
                            @endswitch
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación estilo cliente de correo -->
            <div class="border-t border-gray-200 px-4 py-3 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando <span class="font-medium">{{ $tickets->firstItem() }}</span> a <span
                            class="font-medium">{{ $tickets->lastItem() }}</span> de <span class="font-medium">{{
                            $tickets->total() }}</span> tickets
                    </div>

                    <div class="flex items-center space-x-2">
                        @if ($tickets->onFirstPage())
                        <button disabled class="px-3 py-1 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        @else
                        <a href="{{ $tickets->appends(['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'estado' => $estado, 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : ''])->previousPageUrl() }}"
                            class="px-3 py-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        @endif

                        <span class="text-sm text-gray-600">
                            Página {{ $tickets->currentPage() }} de {{ $tickets->lastPage() }}
                        </span>

                        @if ($tickets->hasMorePages())
                        <a href="{{ $tickets->appends(['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'estado' => $estado, 'fecha' => isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : ''])->nextPageUrl() }}"
                            class="px-3 py-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        @else
                        <button disabled class="px-3 py-1 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra lateral derecha con filtros -->
        <div class="hidden lg:block w-80 border border-gray-200 p-4 bg-gray-50 overflow-y-auto"
            style="max-height: calc(100vh - 160px);">
            <div class="space-y-4">
                <!-- Acciones rápidas -->
                <div class="pt-1">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>Acciones Rápidas
                    </h4>
                    <div class="flex space-x-2">
                        <a href="{{ route('tickets.create') }}"
                            class="flex items-center justify-center px-4 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Crear Ticket
                        </a>
                        <a href="{{ route('tickets:reporte', array_merge(request()->only(['cliente_id', 'tags_id', 'fecha', 'urgente', 'mis_tickets', 'buscar']), ['cliente_id' => $cliente_id, 'tags_id' => $tags_id ?: '', 'estado'=> $estado, 'fecha'=> isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : ''])) }}"
                            target="_blank"
                            class="flex items-center justify-center px-4 py-1 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <i class="fas fa-file-export mr-2"></i>
                            Exportar Reporte
                        </a>
                    </div>
                </div>

                <!-- Título -->
                <div class="flex items-center justify-between pb-3 border-b border-gray-300">
                    <h3 class="font-semibold text-lg text-gray-800">
                        <i class="fas fa-sliders-h mr-2 text-blue-600"></i>Filtros y Acciones
                    </h3>
                    <button onclick="resetFilters()" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                        <i class="fas fa-redo mr-1"></i>Limpiar
                    </button>
                </div>

                <!-- Formulario de filtros -->
                <form action="" id="filtrar" class="space-y-4">
                    <input type="hidden" id="estado" name="estado" value="{{ $estado }}">
                    <input type="hidden" id="vit" name="vit" value="{{ request('vit') }}">
                    <input type="hidden" id="urgente" name="urgente" value="{{ request('urgente') }}">
                    <input type="hidden" id="mis_tickets" name="mis_tickets" value="{{ request('mis_tickets') }}">

                    <!-- Buscar -->
                    <div>
                        <x-label for="buscar" class="text-gray-700 font-semibold mb-2 flex items-center text-sm">
                            <i class="fas fa-search mr-2 text-gray-500"></i>Buscar Ticket
                        </x-label>
                        <x-input type="text" id="buscar" class="w-full filtro text-sm" name="buscar"
                            placeholder="Título, descripción o #ticket..." value="{{ $buscar }}" />
                    </div>

                    <!-- Fecha -->
                    <div>
                        <x-label for="fecha" class="text-gray-700 font-semibold mb-2 flex items-center text-sm">
                            <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Rango de Fechas
                        </x-label>
                        <x-input type="text" id="fecha" class="w-full filtro text-sm" name="fecha"
                            value='{{ isset($fechas[1]) ? "$fechas[0] - $fechas[1]" : "" }}'
                            placeholder="Seleccionar rango..." autocomplete='off' />
                    </div>

                    <!-- Tags -->
                    <div>
                        <x-label for="tags_id" class="text-gray-700 font-semibold mb-2 flex items-center text-sm">
                            <i class="fas fa-tags mr-2 text-gray-500"></i>Etiquetas
                        </x-label>
                        <x-select name='tags_id' class="filtro tags form-control w-full text-sm">
                            <option value="">Todas las etiquetas</option>
                            @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ (is_array($tags_id) ? in_array($tag->id, $tags_id) :
                                $tags_id == $tag->id) ? 'selected' : '' }}>{{ $tag->nombre }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </form>

                <!-- Estadísticas rápidas -->
                <div class="pt-4 border-t border-gray-300">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Resumen Actual
                    </h4>
                    <div class="space-y-2">
                        <div
                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-ticket-alt text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-gray-600 text-sm">Total Tickets</span>
                            </div>
                            <span class="font-bold text-gray-800 text-lg">{{ $tickets->total() }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-list text-green-600 text-sm"></i>
                                </div>
                                <span class="text-gray-600 text-sm">En esta página</span>
                            </div>
                            <span class="font-bold text-gray-800 text-lg">{{ $tickets->count() }}</span>
                        </div>
                        @if($estado)
                        <div
                            class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-filter text-purple-600 text-sm"></i>
                                </div>
                                <span class="text-gray-600 text-sm">Estado</span>
                            </div>
                            <span class="font-semibold text-gray-800 text-sm capitalize">{{ $estado }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Reset filters function
        function resetFilters() {
            window.location.href = '{{ route("tickets.index") }}';
        }
    </script>

    <script>
        // Función para expandir/colapsar hilos de conversación
        function toggleThread(ticketId) {
            const thread = document.getElementById('thread-' + ticketId);
            const icon = document.getElementById('icon-' + ticketId);

            if (thread.classList.contains('hidden')) {
                thread.classList.remove('hidden');
                thread.classList.add('animate-fadeIn');
                icon.style.transform = 'rotate(90deg)';
            } else {
                thread.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .toggle-thread:hover {
            text-decoration: underline;
        }

        #thread- {
                {
                $ticket->id ?? 'placeholder'
            }
        }

            {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Estilos para el scroll en los hilos */
        #thread- {
                {
                $ticket->id ?? 'placeholder'
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        #thread- {
                {
                $ticket->id ?? 'placeholder'
            }
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        #thread- {
                {
                $ticket->id ?? 'placeholder'
            }
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        #thread- {
                {
                $ticket->id ?? 'placeholder'
            }
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Estilos para la barra lateral derecha */
        .w-80 {
            width: 20rem;
        }

        /* Scrollbar personalizado para barra lateral derecha */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>

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
