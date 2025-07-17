<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/cruds/tareas.css') }}">
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tareas.index') }}">Tareas</a>
                    / <span class="font-bold">Ver</span>
                </span>
            </div>
            <div class="basis-2/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ $tarea->nombre }}
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
        <!-- Información principal del tarea -->
        <tr>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Cliente: </strong>
                {{ $tarea->cliente->razon_social }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Encargado: </strong>
                {{ $tarea->encargado ? $tarea->encargado->user->name : 'Sin asignar' }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Enfoque: </strong> {{ $tarea->enfoque ? $tarea->enfoque : 'Sin asignar' }}
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Descripción: </strong><br>
                <p>{!! nl2br(e($tarea->descripcion)) !!}</p>
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Estado: </strong>
                @switch($estado = $tarea->estado)
                    @case('creado')
                        <span class="text-yellow-600 font-bold">Creado</span>
                        @break
                    @case('en_progreso')
                        <span class="text-green-400 font-bold">En Progreso</span>
                        @break
                    @case('completada')
                        <span class="text-green-600 font-bold">Completada</span>
                        @break
                    @case('cancelada')
                        <span class="text-red-600 font-bold">Cancelada</span>
                        @break
                @endswitch
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Prioridad: </strong>
                <span class="prioridad-{{ $tarea->prioridad }}">{{ ucfirst($tarea->prioridad) }}</span>
            </td>
        </tr>
    </table>
    <script src="{{ asset('js/cruds/tareas.js?id=03') }}" defer></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        let tarea = "@json($tarea->id)"
    </script>
</x-app-layout>
