<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline"
                        href="{{ route('clientes.index') }}">Clientes</a></span> / <span
                    class="text-sm text-gray-800 font-bold">Editar</span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('EDITAR CLIENTE') }}
                </h2>
            </div>
            @if (session('error'))
                <div class="basis-1/3">
                    <x-small-message
                        class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ session('error') }}</x-small-message>
                </div>
            @endif
        </div>
    </x-slot>

    <form action="{{ route('clientes.update', $cliente->id) }}" method="post"
        class="flex flex-row flex-wrap space-x-4 space-y-4">
        @csrf
        @method('PUT')
        <!-- NIT -->
        <div class="md:basis-1/6 basis-1/3 px-2">
            <x-label for="nit" :value="__('NIT')" />
            <x-input id="nit" class="block mt-1 w-full" type="text" name="nit" :value="old('nit') ? old('nit') : $cliente->nit"
                autofocus />
            @error('nit')
                <x-small class="text-red-600">{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre -->
        <div class="md:basis-1/4 basis-2/3 px-2">
            <x-label for="nombre" :value="__('Nombre')" />
            <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre') ? old('nombre') : $cliente->razon_social" />
            @error('nombre')
                <x-small class="text-red-600">{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Dirección -->
        <div class="md:basis-4/12 basis-full px-2">
            <x-label for="direccion" :value="__('Dirección')" />
            <x-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion') ? old('direccion') : $cliente->user->direccion" />
            @error('direccion')
                <x-small class="text-red-600">{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Teléfono -->
        <div class="md:basis-2/12 basis-4/12 px-2">
            <x-label for="telefono" :value="__('Teléfono')" />
            <x-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono') ? old('telefono') : $cliente->user->telefono" />
            @error('telefono')
                <x-small class="text-red-600">{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Celular -->
        <div class="md:basis-2/12 basis-4/12 px-2">
            <x-label for="celular" :value="__('Celular')" />
            <x-input id="celular" class="block mt-1 w-full" type="text" name="celular" :value="old('celular') ? old('celular') : $cliente->user->celular" />
            @error('celular')
                <x-small class="text-red-600">{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Correo -->
        <div class="md:basis-1/4 basis-1/2 px-2">
            <x-label for="correo" :value="__('Correo')" />
            <x-input id="correo" class="block mt-1 w-full" type="text" name="correo" :value="old('correo') ? old('correo') : $cliente->user->email" />
            @error('correo')
                <x-small class="text-red-600">{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Identificación Encargado -->
        <div class="md:basis-2/12 basis-1/2 px-2">
            <x-label for="identificacion_encargado" :value="__('Identificación Encargado')" />
            <x-input id="identificacion_encargado" class="block mt-1 w-full" type="text"
                name="identificacion_encargado" :value="old('identificacion_encargado')
                    ? old('identificacion_encargado')
                    : $cliente->identificacion_encargado" />
            @error('identificacion_encargado')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre Encargado -->
        <div class="md:basis-4/12 basis-1/2 px-2">
            <x-label for="nombre_encargado" :value="__('Nombre Encargado')" />
            <x-input id="nombre_encargado" class="block mt-1 w-full" type="text" name="nombre_encargado"
                :value="old('nombre_encargado') ? old('nombre_encargado') : $cliente->nombre_encargado" />
            @error('nombre_encargado')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- notifated_meddream -->
        <div class="md:basis-1/12 px-2">
            <x-label for="notifated_meddream" :value="__('Notificacion meddream')" />
            <!-- Input del checkbox -->
            <input id="default-checkbox" type="checkbox" name="notifated_meddream"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                data-id="{{ $cliente->id }}" {{ $cliente->notifated_meddream ? 'checked' : ' ' }}>
            <!-- Mostrar errores si los hay -->
            @error('notifated_meddream')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Fecha vencimiento meddream -->
        <div class="md:basis-4/12 basis-1/2 px-2">
            <x-label for="fecha_vencimiento_meddream" :value="__('Fecha vencimiento meddream ')" />
            <x-input id="fecha_vencimiento_meddream" class="block mt-1 w-full" type="datetime-local"
                name="fecha_vencimiento_meddream" :value="old('fecha_vencimiento_meddream')
                    ? old('fecha_vencimiento_meddream')
                    : $cliente->fecha_vencimiento_meddream" />
            @error('fecha_vencimiento_meddream')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button class="self-end">Guardar</x-button>
        </div>
    </form>
</x-app-layout>
