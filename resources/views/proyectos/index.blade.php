<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/bitacora.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="grid grid-cols-3 mb-2">
            <div class="col-start-2">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('PROYECTOS') }}
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-6 items-center mb-2">
            <div>
                <x-anchor href="{{ route('proyectos.create') }}">Crear Proyecto</x-anchor><br><br>
            </div>
            <form action="" id="filtrar" class="col-span-4 grid grid-cols-4 px-3 mb-2">
                <div class="px-2 col-span-2">
                    <x-label for="cliente_id" :value="__('Filtrar por cliente')" />
                    <x-select name='cliente_id' id="cliente_id" class="filtro">
                        @foreach ($clientes as $element)
                            <option value="{{ $element->id }}" {{ $cliente_id == $element->id ? 'selected' : '' }}>
                                {{ $element->razon_social }}
                            </option>
                        @endforeach
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
                <th class="border border-slate-300 px-5 py-1">Nombre</th>
                <th class="border border-slate-300 px-5 py-1">Cliente</th>
                <th class="border border-slate-300 px-5 py-1">Fecha</th>
                <th class="border border-slate-300 px-5 py-1">Descripción</th>
                <th class="border border-slate-300 px-5 py-1">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($proyectos as $proyecto)
                <tr>
                    <td class="border border-slate-300 px-5 py-2">
                        <div class="flex flex-row space-x-2">
                            <x-show-button href="{{ route('proyectos.show', $proyecto->id) }}">
                            </x-show-button>
                            <x-edit-button href="{{ route('proyectos.edit', $proyecto->id) }}">
                            </x-edit-button>
                            <x-delete-button class="eliminar" data-form="eliminar-proyecto-{{ $proyecto->id }}"
                                data-model="Proyecto" href="#">
                            </x-delete-button>
                            <x-delete-form id="eliminar-proyecto-{{ $proyecto->id }}"
                                action="{{ route('proyectos.destroy', $proyecto->id) }}">
                            </x-delete-form>
                        </div>
                    </td>
                    <td class="border border-slate-300 px-5 py-1">{{ $proyecto->nombre }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $proyecto->cliente->razon_social }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ formatDate($proyecto->created_at, "d/m/Y h:i A") }}</td>
                    <td class="border border-slate-300 px-5 py-1 descripcion">{!! Str::length($proyecto->descripcion) > 500 ? Str::substr($proyecto->descripcion, 0, 200)."..." : $proyecto->descripcion !!}</td>
                    <td class="border border-slate-300 px-5 py-1">
                        {{ ucfirst($proyecto->estado) }} ({{ $proyecto->progreso }}%)
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $proyecto->progreso }}%;"></div>
                        </div>                          
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    {{ $proyectos->appends(request()->input())->links() }}
</x-app-layout>
