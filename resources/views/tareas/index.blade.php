<x-app-layout>
    @if (isset($fullscreen))
    <x-slot name="fullscreen">{{ $fullscreen }}</x-slot>
    @endif
    <link rel="stylesheet" href="{{ asset('css/cruds/tareas.css') }}">
    <script src="{{ asset('js/cruds/tareas.js') }}" defer></script>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/2">
                @if (isset($fullscreen) && $fullscreen)
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tareas.index') }}">Tareas</a> /
                    <span class="font-bold">Lista</span></span>
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
        <form action="" id="filtrar">
            <div class="grid grid-cols-4 items-center mb-2">
                <div class="px-2">
                    <x-label for="cliente_id" :value="__('Filtrar por cliente')" />
                    <x-select name='cliente_id' id="cliente_id" class="filtro select2">
                        @foreach ($clientes as $element)
                        <option value="{{ $element->id }}" {{ $cliente_id==$element->id ? 'selected' : '' }}>
                            {{ $element->razon_social }}
                        </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="px-2">
                    <x-label for="encargado_filter" :value="__('Filtrar por encargado')" />
                    <x-select name='encargado_filter' id='encargado_filter' class="filtro select2">
                        @foreach ($encargados as $element)
                        <option value="{{ $element->id }}" {{ $encargado_filter==$element->id ? 'selected' : '' }}>
                            {{ $element->user->name }}
                        </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="px-2">
                    <x-label for="prioridad" :value="__('Filtrar por prioridad')" />
                    <x-select name='prioridad' id='prioridad' class="filtro">
                        <option value="">--</option>
                        <option value="baja" {{ $prioridad=='baja' ? 'selected' : '' }} class="option-prioridad-baja">
                            Baja</option>
                        <option value="media" {{ $prioridad=='media' ? 'selected' : '' }}
                            class="option-prioridad-media">Media</option>
                        <option value="alta" {{ $prioridad=='alta' ? 'selected' : '' }} class="option-prioridad-alta">
                            Alta</option>
                    </x-select>
                </div>
                <div class="px-2">
                    <x-label for="estado" :value="__('Filtrar por estado')" />
                    <x-select name='estado' id='estado' class="filtro select2">
                        <option value="pendiente" {{ $estado=='pendiente' ? 'selected' : '' }} class="option-pendiente">
                            Pendiente</option>
                        <option value="en_progreso" {{ $estado=='en_progreso' ? 'selected' : '' }}
                            class="option-en_progreso">En Progreso</option>
                        <option value="completada" {{ $estado=='completada' ? 'selected' : '' }}
                            class="option-completada">Completada</option>
                        <option value="cancelada" {{ $estado=='cancelada' ? 'selected' : '' }} class="option-cancelada">
                            Cancelada</option>
                    </x-select>
                </div>
            </div>
        </form>
    </x-slot>

    <table class="border-collapse border border-slate-400 w-full @if(!isset($fullscreen)) text-xs @endif">
        <thead>
            <tr>
                @php
                $canEdit = auth()->user()->can('tareas.edit');
                $canDelete = auth()->user()->can('tareas.destroy');
                $canView = auth()->user()->can('tareas.show');
                @endphp
                @if(($canEdit || $canDelete || $canView) && !isset($fullscreen))
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    ACCIONES</th>
                @endif
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    ENCARGADO</th>
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    CLIENTE</th>
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    TAREA</th>
                @if (!isset($fullscreen))
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    PRIORIDAD</th>
                @endif
                @if (!isset($fullscreen))
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    ENFOQUE</th>
                @endif
                <th class="border border-slate-300 px-2 py-1 @if(!isset($fullscreen)) text-xs @else text-lg @endif">
                    ESTADO</th>
            </tr>
        </thead>
        <tbody id="tareas-tbody">
            @include('tareas.partials.tbody', ['tareas' => $tareas, 'encargados' => $encargados])
        </tbody>
    </table>
</x-app-layout>