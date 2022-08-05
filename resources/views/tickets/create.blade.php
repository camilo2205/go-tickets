<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tickets.index') }}">Tickets</a>
                    / <span class="font-bold">Crear</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('CREAR TICKET') }}
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

    <form action="{{ route('tickets.store') }}" method="post" class="flex flex-row flex-wrap space-y-4" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="estado" id="estado" value="creado">
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
        </div>

        <!-- Cliente -->
        <div class="md:basis-1/3 px-2">
            <x-label for="cliente_id" :value="__('Cliente')" />
            <x-select name="cliente_id" id="cliente_id">
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                @endforeach
            </x-select>
            @error('cliente_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Encargado -->
        <div class="md:basis-1/3 px-2">
            <x-label for="funcionario_id" :value="__('Encargado')" />
            <x-select name="funcionario_id" id="funcionario_id">
                @foreach ($funcionarios as $funcionario)
                    <option value="{{ $funcionario->id }}">({{ $funcionario->cargo }}) {{ $funcionario->user->name }}
                    </option>
                @endforeach
            </x-select>
            @error('funcionario_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Prioridad -->
        <div class="md:basis-1/6 px-2">
            <x-label for="prioridad" :value="__('Prioridad')" />
            <x-select name="prioridad" id="prioridad">
                <option value="urgente">Urgente</option>
                <option value="normal">Normal</option>
            </x-select>
            @error('prioridad')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Tipo -->
        <div class="md:basis-1/6 px-2">
            <x-label for="tipo" :value="__('Tipo')" />
            <x-select name="tipo" id="tipo">
                <option value="soporte">Soporte</option>
                <option value="ajuste">Ajuste</option>
                <option value="desarrollo">Desarrollo</option>
                <option value="capacitacion">Capacitacion</option>
            </x-select>
            @error('tipo')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Descripción -->
        <div class="md:basis-1/2 px-2">
            <x-label for="descripcion" :value="__('Descripción')" />
            <x-textarea id="descripcion" class="block mt-1 w-full" type="text" name="descripcion"
                :value="old('descripcion')" />
            @error('descripcion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Soportes -->
        <div class="md:basis-1/2 px-2">
            <x-label for="soportes" :value="__('Soportes')" />
            <x-input type="file" name="soportes[]" id="soportes" class="block mt-1 w-full" accept="image/*"
                multiple />
            @error('soportes')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
    </form>
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
</x-app-layout>
