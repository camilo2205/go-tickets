
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tareas.index') }}">Tareas</a> / <span class="font-bold">Crear</span></span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">{{ __('CREAR TAREA') }}</h2>
            </div>
            @error('error')
                <div class="basis-1/3">
                    <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ $message }}</x-small-message>
                </div>
            @enderror
        </div>
    </x-slot>

    <form action="{{ route('tareas.store') }}" method="post" class="flex flex-row flex-wrap space-y-4">
        @csrf
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
        </div>
        <!-- Nombre -->
        <div class="basis-full px-2">
            <x-label for="nombre" :value="__('Nombre') . ' (*)'" />
            <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" autocomplete="off" />
            @error('nombre')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Cliente -->
        <div class="md:basis-1/2 px-2">
            <x-label for="cliente_id" :value="__('Cliente') . ' (*)'" />
            <x-select name="cliente_id" id="cliente_id" class="select2" autocomplete="organization">
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->razon_social }}</option>
                @endforeach
            </x-select>
            @error('cliente_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Encargado -->
        <div class="md:basis-1/3 px-2">
            <x-label for="encargado_id" :value="__('Encargado')" />
            <x-select name="encargado_id" id="encargado_id" class="select2" autocomplete="name">
                @foreach($encargados as $encargado)
                    <option value="{{ $encargado->id }}" {{ old('encargado_id') == $encargado->id ? 'selected' : '' }}>{{ $encargado->user->name }}</option>
                @endforeach
            </x-select>
            @error('encargado_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Prioridad -->
        <div class="md:basis-1/6 px-2">
            <x-label for="prioridad" :value="__('Prioridad') . ' (*)'" />
            <x-select name="prioridad" id="prioridad" autocomplete="off">
                <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
            </x-select>
            @error('prioridad')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Descripción -->
        <div class="basis-full px-2">
            <x-label for="descripcion" :value="__('Descripción') . ' (*)'" />
            <x-textarea id="descripcion" class="block mt-1 w-full" name="descripcion" placeholder="Descripción de la tarea" autocomplete="off">{{ old('descripcion') }}</x-textarea>
            @error('descripcion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
    </form>
    <script src="{{ asset('js/cruds/tareas.js') }}" defer></script>
</x-app-layout>
