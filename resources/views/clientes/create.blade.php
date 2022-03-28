<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline"
                        href="{{ route('clientes.index') }}">Clientes</a> / <span class="font-bold">Crear</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('CREAR CLIENTE') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('clientes.store') }}" method="post" class="flex flex-row flex-wrap space-y-4">
        @csrf
        <!-- NIT -->
        <div class="md:basis-1/6 basis-1/3 px-2">
            <x-label for="nit" :value="__('NIT')" />
            <x-input id="nit" class="block mt-1 w-full" type="text" name="nit" :value="old('nit')" autofocus />
            @error('nit')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre -->
        <div class="md:basis-4/12 basis-2/3 px-2">
            <x-label for="nombre" :value="__('Nombre')" />
            <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" />
            @error('nombre')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Dirección -->
        <div class="md:basis-5/12 basis-full px-2">
            <x-label for="direccion" :value="__('Dirección')" />
            <x-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" />
            @error('direccion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Teléfono -->
        <div class="md:basis-2/12 basis-4/12 px-2">
            <x-label for="telefono" :value="__('Teléfono')" />
            <x-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" />
            @error('telefono')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Celular -->
        <div class="md:basis-2/12 basis-4/12 px-2">
            <x-label for="celular" :value="__('Celular')" />
            <x-input id="celular" class="block mt-1 w-full" type="text" name="celular" :value="old('celular')" />
            @error('celular')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Correo -->
        <div class="md:basis-1/4 basis-1/2 px-2">
            <x-label for="correo" :value="__('Correo')" />
            <x-input id="correo" class="block mt-1 w-full" type="text" name="correo" :value="old('correo')" />
            @error('correo')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Guardar -->
        <div class="basis-full">
            <x-button class="self-end">Guardar</x-button>
        </div>
    </form>
</x-app-layout>
