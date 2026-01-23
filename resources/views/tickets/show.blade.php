<x-app-layout>
    <x-slot name="header">
        <span class="text-sm text-gray-600">
            <a class="text-blue-600 hover:text-blue-800 underline" href="{{ route('tickets.index') }}">
                <i class="fas fa-ticket-alt mr-1"></i>Tickets
            </a>
            / <span class="font-semibold text-gray-800">Ver</span>
        </span>
        <div class="grid grid-cols-12 gap-2 mb-2 mt-2">
            <!-- Botones de acción -->
            <div class="col-span-12 lg:col-span-5 flex items-end justify-start space-x-2">
                @can('tickets.edit')
                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="inline-flex items-center justify-center px-2 py-1 bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                        <i class="fas fa-edit mr-2"></i>Editar Ticket
                    </a>
                @endcan
                @can('tickets.destroy')
                    @if ($ticket->estado == 'creado')
                        <button type="button" class="eliminar inline-flex items-center justify-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out" data-form="eliminar-ticket-{{ $ticket->id }}" data-model="Ticket">
                            <i class="fas fa-trash mr-2"></i>Eliminar Ticket
                        </button>
                        <x-delete-form id="eliminar-ticket-{{ $ticket->id }}" action="{{ route('tickets.destroy', $ticket->id) }}"></x-delete-form>
                    @endif
                @endcan
            </div>

            <div class="col-span-12 lg:col-span-6">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight mt-1">
                    <i class="fas fa-eye mr-2 text-blue-600"></i>{{ __('TICKET') }} #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                    <span class="text-sm text-gray-600">({{ formatDate($ticket->created_at, 'd/m/Y h:i A') }})</span>
                </h2>
            </div>
            @if (session('error'))
                <div class="col-span-12 lg:col-span-6 flex items-end justify-end">
                    <x-small-message class="bg-red-200 text-red-600 text-center w-full p-1 rounded font-bold">
                        {{ session('error') }}
                    </x-small-message>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-6">
        <!-- Información del Ticket -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1 md:p-2 mb-4">
            <div class="flex flex-row flex-wrap">
                <!-- Título -->
                @if($ticket->titulo)
                    <div class="basis-full p-1 md:p-2">
                        <x-label class="text-blue-900 font-semibold mb-1">
                            <i class="fas fa-heading mr-1"></i>Título
                        </x-label>
                        <p class="text-gray-800 mt-1">{{ $ticket->titulo }}</p>
                    </div>
                @endif

                <!-- Descripción -->
                <div class="basis-full p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-align-left mr-1"></i>Descripción
                    </x-label>
                    <p class="text-gray-800 mt-1">{!! nl2br(e($ticket->descripcion)) !!}</p>
                </div>

                <!-- Cliente -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-building mr-1"></i>Cliente
                    </x-label>
                    <p class="text-gray-800 mt-1">{{ $ticket->cliente->razon_social }}</p>
                </div>

                <!-- Encargado -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-user-tie mr-1"></i>Encargado
                    </x-label>
                    <p class="text-gray-800 mt-1">{{ $ticket->funcionario ? $ticket->funcionario->user->name : 'Sin asignar' }}</p>
                </div>

                <!-- Nombre Solicitante -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-user mr-1"></i>Nombre del Solicitante
                    </x-label>
                    <p class="text-gray-800 mt-1">{{ ucfirst($ticket->nombre_solicitante) }}</p>
                </div>

                <!-- Prioridad -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>Prioridad
                    </x-label>
                    <p class="text-gray-800 mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $ticket->prioridad == 'urgente' ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                            {{ ucfirst($ticket->prioridad) }}
                        </span>
                    </p>
                </div>

                <!-- Nivel SLA -->
                @if($ticket->nivel_sla)
                    <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                        <x-label class="text-blue-900 font-semibold mb-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>Nivel de Soporte (SLA)
                        </x-label>
                        <p class="text-gray-800 mt-1">{{ ucfirst(str_replace('_', ' ', $ticket->nivel_sla)) }}</p>
                    </div>
                @endif

                <!-- Tipo -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-list mr-1"></i>Tipo
                    </x-label>
                    <p class="text-gray-800 mt-1">{{ ucfirst($ticket->tipo) }}</p>
                </div>

                <!-- Estado -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-info-circle mr-1"></i>Estado
                    </x-label>
                    <p class="text-gray-800 mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold 
                            {{ $ticket->estado == 'creado' ? 'bg-yellow-200 text-yellow-800' : '' }}
                            {{ $ticket->estado == 'activo' ? 'bg-blue-200 text-blue-800' : '' }}
                            {{ $ticket->estado == 'resuelto' ? 'bg-green-200 text-green-800' : '' }}">
                            {{ ucfirst($ticket->estado) }}
                        </span>
                    </p>
                </div>

                <!-- Tags -->
                @if($ticket->tags->count() > 0)
                    <div class="basis-full p-1 md:p-2">
                        <x-label class="text-blue-900 font-semibold mb-1">
                            <i class="fas fa-tag mr-1"></i>Tags
                        </x-label>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($ticket->tags as $tag)
                                <span class="px-2 py-1 rounded-full text-gray-700 bg-gray-200 font-semibold text-sm hover:bg-sky-100 transition duration-300">
                                    {{ $tag->nombre }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Soportes Adjuntos -->
        @if($ticket->soportes->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6 mb-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">
                    <i class="fas fa-paperclip mr-2 text-blue-600"></i>Soportes Adjuntos
                </h3>
                <div class="space-y-4">
                    @foreach ($ticket->soportes as $soporte)
                        <div x-data="{ expanded: false }" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <button type="button" @click="expanded = ! expanded" class="w-full text-left text-blue-600 hover:text-blue-800 font-medium text-sm md:text-base flex items-center justify-between">
                                <span>
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Soporte {{ $loop->iteration }}
                                </span>
                                <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': expanded }"></i>
                            </button>
                            <div x-show="expanded" x-collapse class="mt-3">
                                @php
                                    $extension = pathinfo($soporte->ruta, PATHINFO_EXTENSION);
                                @endphp
                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <a href="/{{ $soporte->ruta }}" data-fancybox="gallery">
                                        <img src="/{{ $soporte->ruta }}" alt="Soporte_{{ $soporte->id }}" class="max-w-full h-auto rounded shadow-sm">
                                    </a>
                                @elseif (in_array($extension, ['pdf', 'doc', 'docx']))
                                    <div class="space-y-2">
                                        <a href="/{{ $soporte->ruta }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline flex items-center">
                                            <i class="fas fa-file-{{ $extension == 'pdf' ? 'pdf' : 'word' }} mr-2"></i>
                                            Descargar {{ strtoupper($extension) }}
                                        </a>
                                        @if ($extension == 'pdf')
                                            <iframe src="/{{ $soporte->ruta }}" class="w-full h-96 rounded"></iframe>
                                        @endif
                                    </div>
                                @else
                                    <a href="/{{ $soporte->ruta }}" download class="text-blue-500 hover:text-blue-700 underline flex items-center">
                                        <i class="fas fa-download mr-2"></i>
                                        Descargar archivo
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        </div>
        <div class="col-span-6">
        <!-- Historial de Respuestas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6 mb-4">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-comments mr-2 text-blue-600"></i>Historial de Respuestas
            </h3>
            <div id="td-respuestas" class="space-y-3">
                @if($ticket->respuestas->count() > 0)
                    @foreach ($ticket->respuestas as $respuesta)
                        <div class="flex {{ $respuesta->user->cliente ? 'flex-row' : 'flex-row-reverse' }} space-x-2">
                            <div class="rounded-xl text-sm m-1 p-2 basis-full md:basis-9/12 {{ $respuesta->user->cliente ? 'bg-cyan-300' : 'bg-green-200' }}">
                                <strong>{{ $respuesta->user->name }}
                                    ({{ $respuesta->user->cliente ? 'Cliente' : ($respuesta->user->funcionario ? 'Funcionario' : 'Admin') }})
                                    - {{ formatDate($respuesta->created_at, 'd/m/Y h:i A') }}
                                    {{ $respuesta->cerrar ? '(Cerrado)' : '' }}
                                </strong>
                                <i class="fa-solid fa-check-double {{ $respuesta->visto ? 'text-blue-600' : '' }}"></i><br>
                                {!! nl2br(e($respuesta->cuerpo)) !!}

                                @if ($respuesta->files)
                                    <br>
                                    @php
                                        $files = json_decode($respuesta->files);
                                    @endphp
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($files as $file)
                                            @if (in_array(pathinfo($file->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                <a data-fancybox="gallery" href="/{{ $file->file_path }}">
                                                    <img src="/{{ $file->file_path }}" alt="{{ $file->file_name }}"
                                                        class="w-32 h-32 object-cover rounded">
                                                </a>
                                            @else
                                                <a href="/{{ $file->file_path }}" target="_blank"
                                                    class="text-blue-500 underline">
                                                    {{ $file->file_name }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-4">No hay respuestas aún</p>
                @endif
            </div>
        </div>
        <!-- Formulario de Respuesta -->
        @if ($ticket->estado != 'creado' && $ticket->estado != 'resuelto')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">
                    <i class="fas fa-reply mr-2 text-blue-600"></i>Responder al Ticket
                </h3>
                <!-- Formulario principal -->
                <form action="{{ route('respuestas.store') }}" name="dropzone-form" method="post" class="drozone"
                    id="dropzone-form" enctype="multipart/form-data" class="flex flex-row flex-wrap space-y-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                    <div class="flex flex-col md:flex-row w-full gap-4">
                        <!-- Área de texto -->
                        <div class="w-full md:w-5/6">
                            <x-label for="cuerpo" :value="__('Responder')" />
                            <x-textarea id="cuerpo" class="block mt-1 w-full" type="text" name="cuerpo"
                                :value="old('cuerpo')" autofocus required />
                            @error('cuerpo')
                                <x-small>{{ $message }}</x-small>
                            @enderror
                        </div>

                        <!-- Área de arrastrar y soltar -->
                        <div class="w-full md:w-1/6">
                            <div id="dropzoneDragArea"
                                class="!p-1 dropzone flex flex-col items-center justify-center w-full h-32 md:h-20 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="dz-message flex flex-col items-center justify-center h-full">
                                    <svg class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Haz
                                            clic para subir</span> o arrastra y suelta</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Cualquier tipo de archivo
                                        (MAX.
                                        8MB)</p>
                                </div>
                            </div>
                            @error('file_message')
                                <x-small>{{ $message ?? 'error desconocido' }}</x-small>
                            @enderror
                        </div>
                    </div>

                    <!-- Mensajes predeterminados -->
                    @if (
                        (auth()->user()->roles[0]->name === 'superadmin' || auth()->user()->roles[0]->name === 'funcionario') &&
                            !auth()->user()->func_gotele)
                        <div id="predetermined-messages" class="mt-2 mb-2 w-full">
                            @if ($mensajesFrecuentes->isNotEmpty())
                                @foreach ($mensajesFrecuentes as $mensaje)
                                    <button
                                        class="message-btn px-2 py-1 bg-blue-600 text-sm text-white rounded-md hover:bg-blue-700 mr-2 mb-2"
                                        data-message="{{ $mensaje->cuerpo }}">
                                        {{ $mensaje->cuerpo }}
                                    </button>
                                @endforeach
                            @else
                                <button
                                    class="message-btn px-2 py-1 mt-2 bg-blue-600 text-sm text-white rounded-md hover:bg-blue-700 mr-2"
                                    data-message="Buenos días, corrección realizada.">Buenos días, corrección
                                    realizada.</button>
                                <button
                                    class="message-btn px-2 py-1 mt-2 bg-blue-600 text-sm text-white rounded-md hover:bg-blue-700 mr-2"
                                    data-message="Hola, tu solicitud ha sido procesada.">Buenas tardes, corrección realizada</button>
                                <button
                                    class="message-btn px-2 py-1 mt-2 bg-blue-600 text-sm text-white rounded-md hover:bg-blue-700 mr-2"
                                    data-message="Gracias por tu paciencia. Tu consulta está siendo revisada.">Estudio eliminado</button>
                            @endif
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    <div class="basis-full mt-2">
                        <input type="hidden" name="notificado" id="notificado" value="0">
                        <button type='submit' id="enviar" class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow-sm transition">
                            Enviar &nbsp;&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                        <button type='button' id="cerrar_ticket"
                            class="ml-3 inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow-sm transition">
                            Cerrar &nbsp;&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </button>
                        <button type="button" id="marcar_corregido" class="ml-3 inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded shadow-sm transition">
                            Marcar como Corregido &nbsp;&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Formulario oculto para cerrar ticket -->
                <form action="{{ route('respuestas.store') }}" method="post" id="respuesta-form">
                    @csrf
                    <input type="hidden" name="cerrar" id="cerrar" value="0">
                    <input type="hidden" name="cuerpo" id="cuerpo_text" value="">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                </form>

                <!-- Formulario oculto para marcar como corregido -->
                <form action="{{ route('tickets.marcarCorregido', $ticket->id) }}" method="post" id="marcar-corregido-form">
                    @csrf
                </form>
            </div>
        @endif
        </div>
    </div>
    {{-- <script>
        window._env = {
            PUSHER_APP_ID: '{{ config('app.pusher_app_id') }}',
            PUSHER_APP_KEY: '{{ config('app.pusher_app_key') }}',
            PUSHER_APP_CLUSTER: '{{ config('app.pusher_app_cluster') }}',
            WS_HOST: '{{ config('app.ws_host') }}',
            WS_PORT: '{{ config('app.ws_port') }}',
        };
    </script> --}}
    <script src="{{ asset('js/cruds/tickets.js?id=03') }}" defer></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        let ticket = "@json($ticket->id)"
    </script>
</x-app-layout>
