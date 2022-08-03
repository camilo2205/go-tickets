<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tickets.index') }}">Tickets</a>
                    / <span class="font-bold">Ver</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('VER TICKET') }}
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

    <table class="border-collapse border border-slate-400">
        <tr>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Cliente: </strong><br>
                {{ $ticket->cliente->razon_social }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Encargado: </strong><br>
                {{ $ticket->funcionario ? $ticket->funcionario->name : 'Sin asignar' }}
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
                {{ $ticket->descripcion }}
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
    </table>
</x-app-layout>
