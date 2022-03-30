<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline"
                        href="{{ route('users.index') }}">Usuarios</a> / <span class="font-bold">Crear</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('CREAR USUARIO') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('users.store') }}" method="post" class="flex flex-row flex-wrap space-y-4">
        @csrf
        @if (session('error'))
            <div class="basis-full">
                <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ session('error') }}
                </x-small-message>
            </div>
        @endif
        <!-- Identificación -->
        <div class="md:basis-1/6 basis-1/3 px-2">
            <x-label for="identificacion" :value="__('Identificación')" />
            <x-input id="identificacion" class="block mt-1 w-full" type="text" name="identificacion"
                :value="old('identificacion')" autofocus />
            @error('identificacion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre -->
        <div class="md:basis-4/12 basis-2/3 px-2">
            <x-label for="name" :value="__('Nombres')" />
            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" />
            @error('name')
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
        <!-- Contraseña -->
        <div class="md:basis-1/4 basis-1/2 px-2 relative">
            <x-label for="password" :value="__('Contraseña')" />
            <x-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password')" />
            <button data-status="0" data-input="password" type="button" class="absolute right-4 top-7 show-password">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                        clip-rule="evenodd" />
                    <path
                        d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                </svg>
            </button>
            @error('password')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button class="self-end">Guardar</x-button>
        </div>
    </form>
</x-app-layout>
