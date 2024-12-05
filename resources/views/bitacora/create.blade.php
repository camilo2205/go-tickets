<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/bitacora.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('bitacora.index') }}">Bitacora</a>
                    / <span class="font-bold">Crear</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('CREAR REGISTRO') }}
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

    <form action="{{ route('bitacora.store') }}" method="post" class="flex flex-row flex-wrap space-y-4"
        enctype="multipart/form-data">
        @csrf
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
        </div>

        <!-- Nombre -->
        <div class="md:basis-1/4 px-2">
            <x-label for="nombre" :value="__('Nombre')" />
            <x-input id="nombre" class="block w-full" type="text" name="nombre">
                {{ old('nombre') }}
            </x-input>
            @error('nombre')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Proyecto -->
        <div class="md:basis-1/3 px-2">
            <x-label for="proyecto" :value="__('Proyecto')" />
            <x-select name="proyecto" id="proyecto" class="form-control">
                @foreach ($proyectos as $proyecto)
                    @if ($proyecto->proyecto)
                        <option value="{{ $proyecto->proyecto}}" 
                            @if(old('proyecto')) @if(in_array($proyecto->proyecto, old('proyecto'))) selected @endif @endif>
                            {{ $proyecto->proyecto }}
                        </option>
                    @endif
                @endforeach
            </x-select>
            @error('proyecto')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Cliente -->
        <div class="md:basis-1/3 px-2">
            <x-label for="cliente_id" :value="__('Cliente')" />
            @if ($cliente)
                <strong>{{ $cliente->razon_social }}</strong>
                <input type="hidden" id="cliente_id" name="cliente_id" value="{{ $cliente->id }}">
            @else
                <x-select name="cliente_id" id="cliente_id">
                    @foreach ($clientes as $client)
                        <option value="{{ $client->id }}" @if ($client->id == old('cliente_id')) selected @endif>
                            {{ $client->razon_social }}
                        </option>
                    @endforeach
                </x-select>
            @endif
            @error('cliente_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Encargado -->
        <div class="md:basis-1/3 px-2">
            <x-label for="funcionario_id" :value="__('Encargado')" />
            <x-select name="funcionario_id" id="funcionario_id">
                @foreach ($funcionarios as $funcionario)
                    <option value="{{ $funcionario->id }}" @if ($funcionario->id == old('funcionario_id')) selected @endif>
                        {{ $funcionario->user->name }}
                    </option>
                @endforeach
            </x-select>
            @error('funcionario_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Tiempo -->
        <div class="md:basis-1/4 px-2">
            <x-label for="inicio_fin" :value="__('Inicio (A-M-D H:M) - Fin (A-M-D H:M)')" />
            <x-input id="inicio_fin" class="block w-full datetime" type="text" name="inicio_fin">
                {{ old('inicio_fin') }}
            </x-input>
            @error('inicio_fin')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        
        <!-- Esfuerzo-->
        <div class="md:basis-1/4 px-2">
            <x-label for="esfuerzo" :value="__('Esfuerzo')" />
            <x-input id="esfuerzo" class="block mt-1 w-full" type="number" name="esfuerzo">
                {{ old('esfuerzo') }}
            </x-input>
            @error('esfuerzo')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Descripción -->
        <div class="md:basis-full px-2">
            <x-label for="descripcion" :value="__('Descripción')" />
            <x-input hidden id="descripcion" class="block mt-1 w-full" type="text" name="descripcion">
                {{ old('descripcion') }}
            </x-input>
            @error('descripcion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
    </form>
    <x-slot name='scriptsjs'>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    </x-slot>
    <script src="{{ asset('js/cruds/bitacora.js') }}" defer></script>
</x-app-layout>
