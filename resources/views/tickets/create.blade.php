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

    <form action="{{ route('tickets.store') }}" method="post" class="flex flex-row flex-wrap space-y-4"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="estado" id="estado" value="creado">
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
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

        @if (!$cliente)
            <!-- Encargado -->
            <div class="md:basis-1/3 px-2">
                <x-label for="funcionario_id" :value="__('Encargado')" />
                <x-select name="funcionario_id" id="funcionario_id">
                    @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" @if ($funcionario->id == old('funcionario_id')) selected @endif>
                            ({{ $funcionario->cargo }})
                            {{ $funcionario->user->name }}
                        </option>
                    @endforeach
                </x-select>
                @error('funcionario_id')
                <x-small>{{ $message ?? 'Error desconocido' }}</x-small>
                @enderror
            </div>
        @endif
        <!-- Tags -->
        <div class="md:basis-1/3 px-5">
            <x-label for="Tags" :value="__('Tags')" />
            <x-select multiple="multiple" name="tags[]" id="tags" class="tags form-control">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}"> {{ $tag->nombre }}</option>
                @endforeach
            </x-select>
            @error('tags')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Prioridad -->
        <div class="md:basis-1/6 px-2">
            <x-label for="prioridad" :value="__('Prioridad')" />
            <x-select name="prioridad" id="prioridad">
                <option value="urgente" @if ('urgente' == old('prioridad')) selected @endif>Urgente</option>
                <option value="normal"
                    @if ('normal' == old('prioridad')) selected @elseif(!old('prioridad')) selected @endif>
                    Normal</option>
            </x-select>
            @error('prioridad')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Tipo -->
        <div class="md:basis-1/6 px-2">
            <x-label for="tipo" :value="__('Tipo')" />
            <x-select name="tipo" id="tipo">
                <option value="soporte"
                    @if ('soporte' == old('tipo')) selected @elseif(!old('tipo')) selected @endif>
                    Soporte</option>
                <option value="ajuste" @if ('ajuste' == old('tipo')) selected @endif>Ajuste</option>
                <option value="desarrollo" @if ('desarrollo' == old('tipo')) selected @endif>Desarrollo</option>
                <option value="capacitacion" @if ('capacitacion' == old('tipo')) selected @endif>Capacitacion</option>
            </x-select>
            @error('tipo')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre del Solicitante -->
        <div class="md:basis-1/2 px-2">
            <x-label for="nombre_solicitante" :value="__('Nombre del Solicitante')" />
            <x-input id="nombre_solicitante" class="block mt-1 w-full" type="text" name="nombre_solicitante" placeholder="Ej: Sebastian Suarez"
                :value="old('nombre_solicitante')"/>
            @error('nombre_solicitante')
                <x-small class="text-red-500">{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Descripción -->
        <div class="md:basis-1/2 px-2">
            <x-label for="descripcion" :value="__('Descripción')" />
            <x-textarea id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" placeholder="Descripción solicitud">
                {{ old('descripcion') }}
            </x-textarea>
            @error('descripcion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Soportes -->
        <div class="md:basis-1/2 px-2">
            <x-label for="soportes" :value="__('Soportes')" />
            <x-input type="file" name="soportes[]" id="soportes" class="block mt-1 w-full" 
                accept="image/*,.pdf,.doc,.docx" multiple />
            @error('soportes')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
    </form>
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
</x-app-layout>
