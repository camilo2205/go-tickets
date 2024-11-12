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
                <p>{!! nl2br(e($ticket->descripcion))!!}</p>
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
                        @if ($respuesta->visto == 0)
                        class="rounded-xl m-1 p-3 basis-7/12 {{ $respuesta->user->cliente ? 'bg-cyan-300' : 'bg-green-200' }}">
                        <strong>{{ $respuesta->user->name }}
                            ({{ $respuesta->user->cliente ? 'Cliente' : ($respuesta->user->funcionario ? 'Funcionario' : 'Admin') }})
                            - {{ formatDate($respuesta->created_at, 'd/m/Y h:i A') }}
                            {{ $respuesta->cerrar ? '(Cerrado)' : '' }}
                        </strong><i class="fa-solid fa-check-double"></i><br>
                        {!! nl2br(e($respuesta->cuerpo))!!}
                        @else
                        class="rounded-xl m-1 p-3 basis-7/12 {{ $respuesta->user->cliente ? 'bg-cyan-300' : 'bg-green-200' }}">
                        <strong>{{ $respuesta->user->name }}
                            ({{ $respuesta->user->cliente ? 'Cliente' : ($respuesta->user->funcionario ? 'Funcionario' : 'Admin') }})
                            - {{ formatDate($respuesta->created_at, 'd/m/Y h:i A') }}
                            {{ $respuesta->cerrar ? '(Cerrado)' : '' }}
                        </strong><i class="fa-solid fa-check-double text-blue-600"></i><br>
                        {!! nl2br(e($respuesta->cuerpo))!!}
                        @endif
                        </div>
                    </div>
                @endforeach
            </td>
        </tr>
        @if ($ticket->estado != 'creado' && $ticket->estado != 'resuelto')
            <tr>
                <td class="border border-slate-300 px-5 py-1" colspan="4">
                    <form action="{{ route('respuestas.store') }}" method="post" id="respuesta-form"
                        class="flex flex-row flex-wrap space-y-4">
                        @csrf
                        <!-- Responder -->
                        <div class="basis-2/3 px-2">
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <x-label for="cuerpo" :value="__('Responder')" />
                            <x-textarea id="cuerpo" class="block mt-1 w-full" type="text" name="cuerpo"
                                :value="old('cuerpo')" autofocus required />
                            @error('cuerpo')
                                <x-small>{{ $message }}</x-small>
                            @enderror
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
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
    <script>
        let ticket = "@json($ticket->id)"
    </script>
</x-app-layout>
