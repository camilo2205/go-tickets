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
                <x-anchor href="/tickets/reporte?cliente_id={{ $cliente_id }}&estado={{ $estado }}&fecha={{ isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '' }}" target="_blank">
                    Reporte &nbsp;
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </x-anchor>
            </div>
        </div>
        <div class="grid grid-cols-6 items-center mb-2">
            <div>
                <x-anchor href="{{ route('tickets.create') }}">Crear Ticket</x-anchor>
            </div>
            <form action="" id="filtrar" class="col-span-4 grid grid-cols-3 px-3">
                @if (is_null($cliente))
                    <div class="px-2">
                        <x-label for="cliente_id" :value="__('Filtrar por cliente')" />
                        <x-select name='cliente_id' class="filtro">
                            @foreach ($clientes as $element)
                                <option value="{{ $element->id }}" {{ $cliente_id == $element->id ? 'selected' : '' }}>
                                    {{ $element->razon_social }}</option>
                            @endforeach
                        </x-select>
                    </div>
                @endif
                <div class="px-2">
                    <x-label for="estado" :value="__('Filtrar por estado')" />
                    <x-select name='estado' class="filtro">
                        <option value="creado" {{ $estado == 'creado' ? 'selected' : '' }}>Creado</option>
                        <option value="asignado" {{ $estado == 'asignado' ? 'selected' : '' }}>Asignado</option>
                        <option value="atendido" {{ $estado == 'atendido' ? 'selected' : '' }}>Atendido</option>
                        <option value="resuelto" {{ $estado == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                    </x-select>
                </div>
                <div class="px-2">
                    <x-label for="fecha" :value="__('Filtrar por fecha')" />
                    <x-input type="text" id="fecha" class="w-full filtro" name="fecha"
                        value='{{ isset($fechas[1]) ? "$fechas[0] - $fechas[1]" : "" }}' autocomplete='off' />
                </div>
            </form>
            <div>
                @if (session('success'))
                    <div class="basis-full">
                        <x-small-message class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                            {{ session('success') }}
                        </x-small-message>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <table class="border-collapse border border-slate-400 w-full">
        <thead>
            <tr>
                <th class="border border-slate-300 px-5 py-1">Acciones</th>
                <th class="border border-slate-300 px-5 py-1">Cliente</th>
                <th class="border border-slate-300 px-5 py-1">Fecha</th>
                <th class="border border-slate-300 px-5 py-1">Encargado</th>
                <th class="border border-slate-300 px-5 py-1">Estado</th>
                <th class="border border-slate-300 px-5 py-1">Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td class="border border-slate-300 px-5 py-2">
                        <div class="flex flex-row space-x-2">
                            <x-show-button href="{{ route('tickets.show', $ticket->id) }}">
                            </x-show-button>
                            @if ($ticket->estado == 'creado' || (!$cliente && !$funcionario))
                                <x-edit-button href="{{ route('tickets.edit', $ticket->id) }}">
                                </x-edit-button>
                                <x-delete-button class="eliminar" data-form="eliminar-ticket-{{ $ticket->id }}"
                                    data-model="Ticket" href="#">
                                </x-delete-button>
                                <x-delete-form id="eliminar-ticket-{{ $ticket->id }}"
                                    action="{{ route('tickets.destroy', $ticket->id) }}">
                                </x-delete-form>
                            @endif
                        </div>
                    </td>
                    <td class="border border-slate-300 px-5 py-1">{{ $ticket->cliente->razon_social }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ formatDate($ticket->created_at) }}</td>
                    <td class="border border-slate-300 px-5 py-1">
                        {{ $ticket->funcionario ? $ticket->funcionario->user->name : '' }}
                    </td>
                    <td class="border border-slate-300 px-5 py-1 {{ $ticket->estado }}">{{ $ticket->estado }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ ucfirst($ticket->descripcion) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    {{ $tickets->links() }}
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
</x-app-layout>
