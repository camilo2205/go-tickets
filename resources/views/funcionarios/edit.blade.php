<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline"
                        href="{{ route('funcionarios.index') }}">Funcionarios</a></span> / <span
                    class="text-sm text-gray-800 font-bold">Editar</span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('EDITAR FUNCIONARIO') }}
                </h2>
            </div>
            @if (session('error'))
                <div class="basis-1/3">
                    <x-small-message class="bg-red-200 text-red-600 w-fit p-1 rounded font-bold">{{ session('error') }}</x-small-message>
                </div>
            @endif
        </div>
    </x-slot>

    <form action="{{ route('funcionarios.update', $funcionario->id) }}" method="post" class="flex flex-row flex-wrap space-x-4 space-y-4">
        @csrf
        @method('PUT')
        <!-- Identificación -->
        <div class="md:basis-1/6 basis-1/3 px-2">
            <x-label for="identificacion" :value="__('Identificación')" />
            <x-input id="identificacion" class="block mt-1 w-full" type="text" name="identificacion" :value="old('identificacion') ? old('identificacion') : $funcionario->user->identificacion" autofocus />
            @error('identificacion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Nombre -->
        <div class="md:basis-4/12 basis-2/3 px-2">
            <x-label for="nombre" :value="__('Nombre')" />
            <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('name') ? old('name') : $funcionario->user->name" />
            @error('nombre')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Teléfono -->
        <div class="md:basis-2/12 basis-4/12 px-2">
            <x-label for="telefono" :value="__('Teléfono')" />
            <x-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono') ? old('telefono') : $funcionario->user->telefono" />
            @error('telefono')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Celular -->
        <div class="md:basis-2/12 basis-4/12 px-2">
            <x-label for="celular" :value="__('Celular')" />
            <x-input id="celular" class="block mt-1 w-full" type="text" name="celular" :value="old('celular') ? old('celular') : $funcionario->user->celular" />
            @error('celular')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Cargo -->
        <div class="md:basis-3/12 basis-2/3 px-2">
            <x-label for="cargo" :value="__('Cargo')" />
            <x-input id="cargo" class="block mt-1 w-full" type="text" name="cargo" :value="old('cargo') ? old('cargo') : $funcionario->cargo" />
            @error('cargo')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Dirección -->
        <div class="md:basis-5/12 basis-full px-2">
            <x-label for="direccion" :value="__('Dirección')" />
            <x-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion') ? old('direccion') : $funcionario->user->direccion" />
            @error('direccion')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Correo -->
        <div class="md:basis-1/4 basis-1/2 px-2">
            <x-label for="correo" :value="__('Correo')" />
            <x-input id="correo" class="block mt-1 w-full" type="text" name="correo" :value="old('email') ? old('email') : $funcionario->user->email" />
            @error('correo')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button class="self-end">Guardar</x-button>
        </div>
    </form>
</x-app-layout>
