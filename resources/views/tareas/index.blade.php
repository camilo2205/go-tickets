<x-app-layout>
    @if (isset($fullscreen))
        <x-slot name="fullscreen">{{ $fullscreen }}</x-slot>
    @endif
    <link rel="stylesheet" href="{{ asset('css/cruds/tareas.css') }}">
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/2">
                @if (isset($fullscreen) && $fullscreen)
                    <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tareas.index') }}">Tareas</a> / <span class="font-bold">Lista</span></span>
                @else
                    <x-anchor href="{{ route('tareas.index', ['fullscreen' => true]) }}">Ver pantalla completa</x-anchor>
                    <x-anchor href="{{ route('tareas.create') }}">Agregar</x-anchor>
                @endif
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('TAREAS') }}
                </h2>
            </div>
            <div class="basis-1/3 self-start">
                @if(session('success'))
                <div class="basis-full">
                    <x-small-message class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                        {{ session('success') }}
                    </x-small-message>
                </div>
                @endif
                @if(session('error'))
                    <div class="basis-full">
                        <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ session('error') }}
                        </x-small-message>
                    </div>
                @endif
                @if($errors->any())
                    <div class="basis-full">
                        <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">
                            {{ $errors->first() }}
                        </x-small-message>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <table class="border-collapse border border-slate-400 w-full text-xs">
        <thead>
            <tr>
                @php
                    $canEdit = auth()->user()->can('tareas.edit');
                    $canDelete = auth()->user()->can('tareas.destroy');
                    $canView = auth()->user()->can('tareas.show');
                @endphp
                @if($canEdit || $canDelete || $canView)
                <th class="border border-slate-300 px-2 py-1 text-xs">Acciones</th>
                @endif
                <th class="border border-slate-300 px-2 py-1 text-xs">Encargado</th>
                <th class="border border-slate-300 px-2 py-1 text-xs">Cliente</th>
                <th class="border border-slate-300 px-2 py-1 text-xs">Nombre</th>
                <th class="border border-slate-300 px-2 py-1 text-xs">Prioridad</th>
                <th class="border border-slate-300 px-2 py-1 text-xs">Enfoque</th>
                <th class="border border-slate-300 px-2 py-1 text-xs">Estado</th>
            </tr>
            </tr>
        </thead>
        <tbody>
            @foreach ($tareas as $tarea)
            <tr class="prioridad-{{ $tarea->prioridad }}-row estado-{{ $tarea->estado }}-tr">
                @if($canEdit || $canDelete || $canView)
                <td class="border border-slate-300 px-2 py-1 text-xs">
                    <div class="flex flex-row space-x-2">
                        <x-show-button href="{{ route('tareas.show', $tarea->id) }}"></x-show-button>
                        @if($tarea->estado == 'pendiente')
                            {{-- Edit button removed as requested --}}
                            @can('tareas.destroy')
                                <x-delete-button class="eliminar" data-form="eliminar-tarea-{{ $tarea->id }}" data-model="Tarea" href="#"></x-delete-button>
                                <x-delete-form id="eliminar-tarea-{{ $tarea->id }}" action="{{ route('tareas.destroy', $tarea->id) }}"></x-delete-form>
                            @endcan
                        @endif
                    </div>
                </td>
                @endif
                <td class="border border-slate-300 px-2 py-1 text-xs">
                    <form method="POST" action="{{ route('tareas.updateEncargado', $tarea->id) }}" class="flex items-center space-x-1">
                        @csrf
                        @method('PATCH')
                        <select name="encargado_id" class="border rounded py-1 text-xs" onchange="this.form.submit()">
                            <option value="">--</option>
                            @foreach($encargados as $encargado)
                                <option value="{{ $encargado->id }}" {{ $tarea->encargado_id == $encargado->id ? 'selected' : '' }}>{{ $encargado->user->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="border border-slate-300 px-2 py-1 text-xs">{{ $tarea->cliente->razon_social ?? '' }}</td>
                <td class="border border-slate-300 px-2 py-1 text-xs">{{ $tarea->nombre }}</td>
                <td class="border border-slate-300 px-2 py-1 text-xs text-center prioridad-{{ $tarea->prioridad }}">
                    <form method="POST" action="{{ route('tareas.updatePrioridad', $tarea->id) }}" class="flex items-center space-x-1">
                        @csrf
                        @method('PATCH')
                        <select name="prioridad" class="border rounded py-1 text-xs @if($tarea->prioridad == 'alta') select-prioridad-alta @elseif($tarea->prioridad == 'media') select-prioridad-media @endif" onchange="this.form.submit()">
                            <option value="">--</option>
                            <option value="baja" {{ $tarea->prioridad == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ $tarea->prioridad == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ $tarea->prioridad == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </form>
                </td>
                <td class="border border-slate-300 px-2 py-1 text-xs text-center">
                    <form method="POST" action="{{ route('tareas.updateEnfoque', $tarea->id) }}" class="flex items-center space-x-1">
                        @csrf
                        @method('PATCH')
                        <select name="enfoque" class="border rounded py-1 text-xs select-enfoque" onchange="this.form.submit()">
                            <option value="">--</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $tarea->enfoque == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </form>
                </td>
                <td class="border border-slate-300 px-2 py-1 text-xs estado-{{ $tarea->estado }}">
                    <form method="POST" action="{{ route('tareas.updateEstado', $tarea->id) }}">
                        @csrf
                        @method('PATCH')
                        <select name="estado" onchange="this.form.submit()" class="border rounded py-1 select-estado">
                            <option value="pendiente" {{ $tarea->estado == 'pendiente' ? 'selected' : '' }} class="option-pendiente">Pendiente</option>
                            <option value="en_progreso" {{ $tarea->estado == 'en_progreso' ? 'selected' : '' }} class="option-en_progreso">En Progreso</option>
                            <option value="completada" {{ $tarea->estado == 'completada' ? 'selected' : '' }} class="option-completada">Completada</option>
                            <option value="cancelada" {{ $tarea->estado == 'cancelada' ? 'selected' : '' }} class="option-cancelada">Cancelada</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/cruds/tareas.js') }}" defer></script>
</x-app-layout>