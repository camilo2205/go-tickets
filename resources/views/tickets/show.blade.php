<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tickets.index') }}">Tickets</a>
                    / <span class="font-bold">Ver</span>
                </span>
            </div>
            <div class="basis-2/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('TICKET') }} #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                    ({{ formatDate($ticket->created_at, 'd/m/Y h:i A') }})
                </h2>
            </div>
            @if (session('error'))
                <div class="basis-1/3">
                    <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ session('error') }}
                    </x-small-message>
                </div>
            @endif
        </div>
    </x-slot>

    <table class="border-collapse border border-slate-400 w-full">
        <tr>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Cliente: </strong><br>
                {{ $ticket->cliente->razon_social }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Encargado: </strong><br>
                {{ $ticket->funcionario ? $ticket->funcionario->user->name : 'Sin asignar' }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Tipo: </strong><br>
                {{ ucfirst($ticket->tipo) }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Prioridad: </strong><br>
                {{ ucfirst($ticket->prioridad) }}
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="4">
                <strong>Descripción: </strong><br>
                <p>{!! nl2br(e($ticket->descripcion)) !!}</p>
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="4">
                <strong>Tags:</strong><br>
                <div class="flex flex-wrap space-x-2 items-end" id="tagsTickets">
                    @foreach ($ticket->tags as $tag)
                        <div>
                            <span id="chip"
                                class="px-4 py-2 rounded-full text-gray-500 bg-gray-200 font-semibold text-sm flex align-center w-max cursor-pointer active:bg-gray-300 hover:scale-110 hover:bg-sky-100 transition duration-300 ease"
                                data-id="{{ $tag->id }}">
                                {{ $tag->nombre }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="4">
                <strong>Soportes: </strong><br>
                @foreach ($ticket->soportes as $soporte)
                    <table class="w-full border-collapse border border-slate-400">
                        <tr x-data="{ expanded: false }" class="py-1">
                            <td class="border border-slate-300">
                                <button @click="expanded = ! expanded" class="w-full ml-2">Soporte
                                    {{ $loop->iteration }}</button>
                                <p x-show="expanded" x-collapse>
                                    <img src="/{{ $soporte->ruta }}" alt="Soporte_{{ $soporte->id }}" class="mt-2">
                                </p>
                            </td>
                        </tr>
                    </table>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="4" id="td-respuestas">
                @foreach ($ticket->respuestas as $respuesta)
                    <div class="flex {{ $respuesta->user->cliente ? 'flex-row' : 'flex-row-reverse' }} space-x-2">
                        <div
                            class="rounded-xl m-1 p-3 basis-7/12 {{ $respuesta->user->cliente ? 'bg-cyan-300' : 'bg-green-200' }}">
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
                                @foreach ($files as $file)
                                    @if (in_array(pathinfo($file->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <a data-fancybox="gallery" href="/{{ $file->file_path }}">
                                            <img src="/{{ $file->file_path }}" alt="{{ $file->file_name }}"
                                                class="w-32 h-32 object-cover">
                                        </a>
                                    @else
                                        <a href="/{{ $file->file_path }}" target="_blank"
                                            class="text-blue-500 underline">
                                            {{ $file->file_name }}
                                        </a>
                                    @endif
                                    <br>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </td>
        </tr>
        @if ($ticket->estado != 'creado' && $ticket->estado != 'resuelto')
            <tr>
                <td class="border border-slate-300 px-5 py-1" colspan="4">
                    <!-- Formulario -->
                    <form action="{{ route('respuestas.store') }}" name="dropzone-form" method="post" class="drozone"
                        id="dropzone-form" enctype="multipart/form-data" class="flex flex-row flex-wrap space-y-4">
                        @csrf
                        <!-- Campos adicionales del formulario -->
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        <!-- Contenedor flex para los campos -->
                        <div class="flex w-full space-x-4">
                            <!-- Textarea -->
                            <div class="w-3/4 px-2">
                                <x-label for="cuerpo" :value="__('Responder')" />
                                <x-textarea id="cuerpo" class="block mt-1 w-full" type="text" name="cuerpo"
                                    :value="old('cuerpo')" autofocus required />
                                @error('cuerpo')
                                    <x-small>{{ $message }}</x-small>
                                @enderror
                            </div>

                            <!-- Dropzone Area -->
                            <div class="w-2/6 px-1">
                                <div id="dropzoneDragArea"
                                    class="!p-1 dropzone flex flex-col items-center justify-center w-full h-50 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
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
                                    <x-small>{{ $message }}</x-small>
                                @enderror
                            </div>

                            {{-- <div class="dropzone-previews"></div> --}}
                        </div>

                        <!-- Guardar -->
                        <div class="basis-full px-2 pb-2">
                            <input type="hidden" name="cerrar" id="cerrar" value="0">
                            <input type="hidden" name="notificado" id="notificado" value="0">
                            <x-button type='submit' id="enviar">
                                Enviar &nbsp;&nbsp;
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </x-button>
                            @if (!$funcionario)
                                <x-button type='button' id="cerrar_ticket"
                                    class="ml-3 bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-700 ring-green-300">
                                    Cerrar &nbsp;&nbsp;
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </x-button>
                            @endif
                        </div>
                    </form>


                </td>
            </tr>
        @endif
    </table>
    <script src="{{ asset('js/cruds/tickets.js?id=03') }}" defer></script>
    <script>
        let ticket = "@json($ticket->id)"
    </script>
</x-app-layout>
