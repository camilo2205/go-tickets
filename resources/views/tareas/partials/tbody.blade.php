@php
$canEdit = auth()->user()->can('tareas.edit');
$canDelete = auth()->user()->can('tareas.destroy');
$canView = auth()->user()->can('tareas.show');
@endphp
@foreach ($tareas as $tarea)
<tr class="tarea prioridad-{{ $tarea->prioridad }}-row estado-{{ $tarea->estado }}-tr" flip-id="{{ $tarea->id }}">
    @if(($canEdit || $canDelete || $canView) && !isset($fullscreen))
    <td class="border border-slate-300 px-2 py-1 text-xs">
        <div class="flex flex-row space-x-2">
            <x-show-button href="{{ route('tareas.show', $tarea->id) }}"></x-show-button>
            @if($tarea->estado == 'pendiente')
            {{-- Edit button removed as requested --}}
            @can('tareas.destroy')
            <x-delete-button class="eliminar" data-form="eliminar-tarea-{{ $tarea->id }}" data-model="Tarea" href="#">
            </x-delete-button>
            <x-delete-form id="eliminar-tarea-{{ $tarea->id }}" action="{{ route('tareas.destroy', $tarea->id) }}">
            </x-delete-form>
            @endcan
            @endif
        </div>
    </td>
    @endif
    <td class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @endif">
        @if (!isset($fullscreen))
        <form method="POST" action="{{ route('tareas.updateEncargado', $tarea->id) }}"
            class="flex items-center space-x-1">
            @csrf
            @method('PATCH')
            <select name="encargado_id" class="border rounded py-1 text-xs" onchange="this.form.submit()">
                <option value="">--</option>
                @foreach($encargados as $encargado)
                <option value="{{ $encargado->id }}" {{ $tarea->encargado_id == $encargado->id ? 'selected' : '' }}>{{
                    $encargado->user->name }}</option>
                @endforeach
            </select>
        </form>
        @else
            {{ $tarea->encargado ? $tarea->encargado->user->name : '' }}
        @endif
    </td>
    <td class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @endif">{{ $tarea->cliente->razon_social ?? '' }}</td>
    <td class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @endif">{{ $tarea->nombre }}</td>
    @if (!isset($fullscreen))
    <td class="border border-slate-300 px-2 py-1 text-xs text-center prioridad-{{ $tarea->prioridad }}">
        <form method="POST" action="{{ route('tareas.updatePrioridad', $tarea->id) }}"
            class="flex items-center space-x-1">
            @csrf
            @method('PATCH')
            <select name="prioridad"
                class="border rounded py-1 text-xs @if($tarea->prioridad == 'alta') select-prioridad-alta @elseif($tarea->prioridad == 'media') select-prioridad-media @endif"
                onchange="this.form.submit()">
                <option value="">--</option>
                <option value="baja" {{ $tarea->prioridad == 'baja' ? 'selected' : '' }}
                    class="option-prioridad-baja">Baja</option>
                <option value="media" {{ $tarea->prioridad == 'media' ? 'selected' : '' }}
                    class="option-prioridad-media">Media</option>
                <option value="alta" {{ $tarea->prioridad == 'alta' ? 'selected' : '' }}
                    class="option-prioridad-alta">Alta</option>
            </select>
        </form>
    </td>
    @endif
    @if (!isset($fullscreen))
    <td class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @endif text-center">
        <form method="POST" action="{{ route('tareas.updateEnfoque', $tarea->id) }}"
            class="flex items-center space-x-1">
            @csrf
            @method('PATCH')
            <select name="enfoque" class="border rounded py-1 text-xs select-enfoque" onchange="this.form.submit()">
                <option value="">--</option>
                @for($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $tarea->enfoque == $i ? 'selected' : '' }}>{{
                    $i }}</option>
                    @endfor
            </select>
        </form>
    </td>
    @endif
    <td class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @endif estado-{{ $tarea->estado }}" nowrap>
        @if (!isset($fullscreen))
        <form method="POST" action="{{ route('tareas.updateEstado', $tarea->id) }}">
            @csrf
            @method('PATCH')
            <select name="estado" class="border rounded py-1 select-estado">
                <option value="pendiente" {{ $tarea->estado == 'pendiente' ? 'selected' : '' }}
                    class="option-pendiente">Pendiente</option>
                <option value="en_progreso" {{ $tarea->estado == 'en_progreso' ? 'selected' : '' }}
                    class="option-en_progreso">En Progreso</option>
                <option value="completada" {{ $tarea->estado == 'completada' ? 'selected' : '' }}
                    class="option-completada">Completada</option>
                <option value="cancelada" {{ $tarea->estado == 'cancelada' ? 'selected' : '' }}
                    class="option-cancelada">Cancelada</option>
            </select>
        </form>
        @else
            @switch($tarea->estado)
                @case('pendiente')
                    <span class="option-pendiente"><strong>PENDIENTE</strong></span>
                    @break
                @case('en_progreso')
                    <span class="option-en_progreso"><strong>EN PROGRESO</strong></span>
                    @break
                @case('completada')
                    <span class="option-completada"><strong>COMPLETADA</strong></span>
                    @break
                @case('cancelada')
                    <span class="option-cancelada"><strong>CANCELADA</strong></span>
                    @break
            @endswitch
        @endif
    </td>
</tr>
@endforeach