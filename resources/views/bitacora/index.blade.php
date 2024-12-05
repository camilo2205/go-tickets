<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/bitacora.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="grid grid-cols-3 mb-2">
            <div class="col-start-2">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('BITACORA') }}
                </h2>
            </div>
            <div>
                <x-anchor href="{{ route('bitacora:reporte', ['cliente_id' => $cliente_id, 'proyecto_filter' => $proyecto_filter, 'fecha'=> isset($fechas[1]) ? $fechas[0].' - '.$fechas[1] : '']) }}" target="_blank">
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
                <x-anchor href="{{ route('proyectos.create') }}">Crear Proyecto</x-anchor><br><br>
                <x-anchor href="{{ route('bitacora.create') }}">Crear Registro</x-anchor>
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
                <div class="px-2 col-span-2">
                    <x-label for="funcionario_id" :value="__('Filtrar por funcionario')" />
                    <x-select name='funcionario_id' id="funcionario_id" class="filtro">
                        @foreach ($funcionarios as $element)
                            <option value="{{ $element->id }}" {{ $funcionario_id == $element->id ? 'selected' : '' }}>
                                {{ $element->user->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="px-2">
                    <x-label for="fecha" :value="__('Filtrar por fecha')" />
                    <x-input type="text" id="fecha" class="w-full filtro" name="fecha"
                        value='{{ isset($fechas[1]) ? "$fechas[0] - $fechas[1]" : "" }}' autocomplete='off' />
                </div>
                <div class="px-2" style="width: 270px">
                    <x-label for="tags_id" :value="__('Filtrar por proyecto')" />
                    <x-select id="proyecto_filter" name='proyecto_filter' class="filtro form-control" >
                        @foreach($proyectos as $proyecto)
                            @if ($proyecto->proyecto)
                                <option value="{{ $proyecto->proyecto }}" {{ $proyecto->proyecto == $proyecto_filter ? 'selected' : '' }}>{{ $proyecto->proyecto }}</option>    
                            @endif
                        @endforeach
                    </x-select>
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
                <th class="border border-slate-300 px-5 py-1">Realizado por</th>
                <th class="border border-slate-300 px-5 py-1">Proyecto</th>
                <th class="border border-slate-300 px-5 py-1">Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bitacora as $registro)
                <tr>
                    <td class="border border-slate-300 px-5 py-2">
                        <div class="flex flex-row space-x-2">
                            <x-show-button href="{{ route('bitacora.show', $registro->id) }}">
                            </x-show-button>
                            <x-edit-button href="{{ route('bitacora.edit', $registro->id) }}">
                            </x-edit-button>
                            <x-delete-button class="eliminar" data-form="eliminar-ticket-{{ $registro->id }}"
                                data-model="Ticket" href="#">
                            </x-delete-button>
                            <x-delete-form id="eliminar-ticket-{{ $registro->id }}"
                                action="{{ route('bitacora.destroy', $registro->id) }}">
                            </x-delete-form>
                        </div>
                    </td>
                    <td class="border border-slate-300 px-5 py-1">{{ $registro->cliente->razon_social }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ formatDate($registro->created_at, "d/m/Y h:i A") }}</td>
                    <td class="border border-slate-300 px-5 py-1">
                        {{ $registro->funcionario ? $registro->funcionario->user->name : '' }}
                    </td>
                    <td class="border border-slate-300 px-5 py-1 {{ $registro->estado }}">
                        {{ $registro->proyecto }}
                    </td>
                    <td class="border border-slate-300 px-5 py-1 descripcion">{!! Str::length($registro->descripcion) > 500 ? Str::substr($registro->descripcion, 0, 200)."..." : $registro->descripcion !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    {{ $bitacora->appends(request()->input())->links() }}
    <script src="{{ asset('js/cruds/bitacora.js?id=02') }}" defer></script>
</x-app-layout>
