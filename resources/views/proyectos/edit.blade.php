<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('proyectos.index') }}">Proyectos</a>
                    / <span class="font-bold">Crear</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('CREAR PROYECTO') }}
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

    <form action="{{ route('proyectos.update', $proyecto->id) }}" method="post" class="flex flex-row flex-wrap space-y-4"
        enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
        </div>

        <!-- Nombre -->
        <div class="md:basis-5/12 px-2">
            <x-label for="nombre" :value="__('Nombre')" />
            <x-input id="nombre" class="block w-full" type="text" name="nombre" :value="old('nombre') ? old('nombre') : $proyecto->nombre">
                {{ old('nombre') }}
            </x-input>
            @error('nombre')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Cliente -->
        <div class="md:basis-1/2 px-2">
            <x-label for="cliente_id" :value="__('Cliente')" />
            <x-select name="cliente_id" id="cliente_id">
                @foreach ($clientes as $client)
                    <option value="{{ $client->id }}" 
                        @if ($client->id == old('cliente_id')) selected @elseif ($client->id == $proyecto->cliente_id && !old('funcionario_id')) selected @endif>
                        {{ $client->razon_social }}
                    </option>
                @endforeach
            </x-select>
            @error('cliente_id')
                <x-small>{{ $message }}</x-small>
            @enderror
        </div>

        <!-- Descripción -->
        <div class="md:basis-full px-2">
            <x-label for="descripcion" :value="__('Descripción')" />
            @error('descripcion')
                <x-small>{{ $message }}</x-small>
            @enderror
            <x-textarea hidden id="descripcion" class="block mt-1 w-full" type="text" name="descripcion">
                @if (old('descripcion'))
                    {{ old('descripcion') }}
                @else 
                    {!! $proyecto->descripcion !!}
                @endif
            </x-textarea>
        </div>
    </form>
    <x-slot name='scriptsjs'>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    </x-slot>
    <script src="{{ asset('js/cruds/bitacora.js') }}" defer></script>
</x-app-layout>
