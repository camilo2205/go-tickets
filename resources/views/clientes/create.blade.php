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
            @if (session('error'))
                <div class="basis-1/3">
                    <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ session('error') }}
                    </x-small-message>
                </div>
            @endif
        </div>
    </x-slot>

    <form action="{{ route('clientes.store') }}" method="post" class="flex flex-row flex-wrap space-y-4">
        @csrf

        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
        </div>

        <!-- NIT -->
        <div class="md:basis-1/6 basis-1/3 px-2">
            <x-label for="nit" :value="__('NIT')" />
            <x-input id="nit" class="block mt-1 w-full" type="text" name="nit" :value="old('nit')"
                autofocus />
            @error('nit')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre -->
        <div class="md:basis-3/12 basis-2/3 px-2">
            <x-label for="nombre" :value="__('Nombre')" />
            <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" />
            @error('nombre')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Dirección -->
        <div class="md:basis-4/12 basis-full px-2">
            <x-label for="direccion" :value="__('Dirección')" />
            <x-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" />
            @error('direccion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <div class="md:basis-2/12 basis-1/6 px-2">
            <x-label for="telefono" :value="__('Teléfono')" />
            <x-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" />
            @error('telefono')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Celular -->
        <div class="md:basis-2/12 basis-1/6 px-2">
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
        <!-- Identificación Encargado -->
        <div class="md:basis-2/12 basis-1/2 px-2">
            <x-label for="identificacion_encargado" :value="__('Identificación Encargado')" />
            <x-input id="identificacion_encargado" class="block mt-1 w-full" type="text"
                name="identificacion_encargado" :value="old('identificacion_encargado')" />
            @error('identificacion_encargado')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre Encargado -->
        <div class="md:basis-4/12 basis-1/2 px-2">
            <x-label for="nombre_encargado" :value="__('Nombre Encargado')" />
            <x-input id="nombre_encargado" class="block mt-1 w-full" type="text" name="nombre_encargado"
                :value="old('nombre_encargado')" />
            @error('nombre_encargado')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

          <!-- notifated_meddream -->
          <div class="md:basis-4/12 basis-1/2 px-2">
            <x-label for="notifated_meddream" :value="__('Notificacion meddream')" />
            <x-input id="notifated_meddream" class="block mt-1 w-full" type="checkbox" name="notifated_meddream"
                :value=" old('notifated_meddream')" />
            @error('notifated_meddream')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
         <!-- Fecha vencimiento meddream -->
         <div class="md:basis-4/12 basis-1/2 px-2">
            <x-label for="fecha_vencimiento_meddream" :value="__('Fecha vencimiento meddream ')" />
            <x-input id="fecha_vencimiento_meddream" class="block mt-1 w-full" type="datetime-local" name="fecha_vencimiento_meddream"
                :value="old('fecha_vencimiento_meddream')" />
            @error('fecha_vencimiento_meddream')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
    </form>
</x-app-layout>
