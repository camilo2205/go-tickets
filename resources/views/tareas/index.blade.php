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
        <tbody id="tareas-tbody">
            @include('tareas.partials.tbody', ['tareas' => $tareas, 'encargados' => $encargados])
        </tbody>
    </table>
    <script src="{{ asset('js/cruds/tareas.js') }}" defer></script>
</x-app-layout>