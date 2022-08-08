<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <x-anchor href="{{ route('tickets.create') }}">Crear Ticket</x-anchor>
            </div>
            <div class="basis-1/4 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('TICKETS') }}
                </h2>
            </div>
            <div class="basis-1/3 self-start">
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

    <table class="border-collapse border border-slate-400">
        <thead>
            <tr>
                <th class="border border-slate-300 px-5 py-1">Acciones</th>
                <th class="border border-slate-300 px-5 py-1">Cliente</th>
                <th class="border border-slate-300 px-5 py-1">Encargado</th>
                <th class="border border-slate-300 px-5 py-1">Tipo</th>
                <th class="border border-slate-300 px-5 py-1">Prioridad</th>
                <th class="border border-slate-300 px-5 py-1">Estado</th>
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
                                <x-delete-button class="eliminar"
                                    data-form="eliminar-ticket-{{ $ticket->id }}" data-model="Ticket" href="#">
                                </x-delete-button>
                                <x-delete-form id="eliminar-ticket-{{ $ticket->id }}"
                                    action="{{ route('tickets.destroy', $ticket->id) }}">
                                </x-delete-form>
                            @endif
                        </div>
                    </td>
                    <td nowrap class="border border-slate-300 px-5 py-1">{{ $ticket->cliente->razon_social }}</td>
                    <td class="border border-slate-300 px-5 py-1">
                        {{ $ticket->funcionario ? $ticket->funcionario->user->name : '' }}
                    </td>
                    <td class="border border-slate-300 px-5 py-1">{{ ucfirst($ticket->tipo) }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ ucfirst($ticket->prioridad) }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ ucfirst($ticket->estado) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
</x-app-layout>
