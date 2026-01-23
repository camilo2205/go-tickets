<x-app-layout>
    <x-slot name="header">
        <span class="text-sm text-gray-600">
            <a class="text-blue-600 hover:text-blue-800 underline" href="{{ route('tickets.index') }}">
                <i class="fas fa-ticket-alt mr-1"></i>Tickets
            </a>
            / <span class="font-semibold text-gray-800">Crear</span>
        </span>
        <div class="grid grid-cols-12 mt-2 mb-2">
            <!-- Guardar -->
            <div class="col-span-12 lg:col-span-5 p-2 md:p-0">
                <button type="button" id="guardar-ticket" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                    <i class="fas fa-save mr-2"></i>Guardar Ticket
                </button>
            </div>

            <div class="col-span-12 lg:col-span-6">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight mt-1">
                    {{ __('CREAR TICKET') }}<i class="fas fa-plus-circle ml-2 text-blue-600"></i>
                </h2>
            </div>


        </div>
    </x-slot>

    {{-- <div id="form-div" class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6"> --}}
        <form action="{{ route('tickets.store') }}" method="post" id="ticket-form" class="flex flex-row flex-wrap"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="estado" id="estado" value="creado">

            <!-- Descripción -->
            <div class="basis-full p-1 md:p-2">
                <x-label for="titulo" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-heading mr-1"></i>Título
                </x-label>
                <x-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" placeholder="Título de la solicitud"
                    :value="old('titulo')" />
                @error('titulo')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="basis-full p-1 md:p-2">
                <x-label for="descripcion" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-align-left mr-1"></i>Descripción
                </x-label>
                <x-textarea id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" placeholder="Descripción de la solicitud">
                    {{ old('descripcion') }}
                </x-textarea>
                @error('descripcion')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Soportes -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="soportes" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-paperclip mr-1"></i>Soportes
                </x-label>
                <x-input type="file" name="soportes[]" id="soportes" class="block mt-1 w-full" 
                    accept="image/*,.pdf,.doc,.docx" multiple />
                @error('soportes')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            @if ($cliente)
                <input type="hidden" id="cliente_id" name="cliente_id" value="{{ $cliente->id }}">
            @else
                <!-- Cliente -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label for="cliente_id" class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-building mr-1"></i>Cliente
                    </x-label>
                        <x-select name="cliente_id" id="cliente_id" class="w-full cliente">
                            @foreach ($clientes as $client)
                                <option value="{{ $client->id }}" @if ($client->id == old('cliente_id')) selected @endif>
                                    {{ $client->razon_social }}
                                </option>
                            @endforeach
                        </x-select>
                    @error('cliente_id')
                        <x-small class="text-red-600">{{ $message }}</x-small>
                    @enderror
                </div>
            @endif

            @if (!$cliente)
                <!-- Encargado -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label for="funcionario_id" class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-user-tie mr-1"></i>Encargado
                    </x-label>
                    <x-select name="funcionario_id" id="funcionario_id" class="w-full">
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}" @if ($funcionario->id == old('funcionario_id')) selected @endif>
                                ({{ $funcionario->cargo }})
                                {{ $funcionario->user->name }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('funcionario_id')
                        <x-small class="text-red-600">{{ $message ?? 'Error desconocido' }}</x-small>
                    @enderror
                </div>
            @endif
            
            <!-- Tags -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="Tags" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-tag mr-1"></i>Tag
                </x-label>
                <x-select name="tags" id="tags" class="tags form-control w-full">
                    <option value="">Seleccionar tag</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @if ($tag->id == old('tags')) selected @endif>{{ $tag->nombre }}</option>
                    @endforeach
                </x-select>
                @error('tags')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>
            
            <!-- Prioridad -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="prioridad" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>Prioridad
                </x-label>
                <x-select name="prioridad" id="prioridad" class="w-full">
                    <option value="urgente" @if ('urgente' == old('prioridad')) selected @endif>Urgente</option>
                    <option value="normal"
                        @if ('normal' == old('prioridad')) selected @elseif(!old('prioridad')) selected @endif>
                        Normal</option>
                </x-select>
                @error('prioridad')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>
            
            @if ($funcionario)
                <!-- nivel sla -->
                <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                    <x-label for="nivel_sla" class="text-blue-900 font-semibold mb-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>Nivel de Soporte (SLA)
                    </x-label>
                    <x-select name="nivel_sla" id="nivel_sla" class="w-full">
                        <option value="nivel_1" @if ('nivel_1' == old('nivel_sla')) selected @endif>Nivel 1</option>
                        <option value="nivel_2" @if ('nivel_2' == old('nivel_sla')) selected @endif>Nivel 2</option>
                        <option value="nivel_3" @if ('nivel_3' == old('nivel_sla')) selected @endif>Nivel 3</option>
                    </x-select>
                    @error('nivel_sla')
                        <x-small class="text-red-600">{{ $message }}</x-small>
                    @enderror
                </div>
            @endif

            <!-- Tipo -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="tipo" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-list mr-1"></i>Tipo
                </x-label>
                <x-select name="tipo" id="tipo" class="w-full">
                    <option value="soporte"
                        @if ('soporte' == old('tipo')) selected @elseif(!old('tipo')) selected @endif>
                        Soporte</option>
                    <option value="ajuste" @if ('ajuste' == old('tipo')) selected @endif>Ajuste</option>
                    <option value="desarrollo" @if ('desarrollo' == old('tipo')) selected @endif>Desarrollo</option>
                    <option value="capacitacion" @if ('capacitacion' == old('tipo')) selected @endif>Capacitacion</option>
                </x-select>
                @error('tipo')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Categoria -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="categoria_id" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-tags mr-1"></i>Categoría
                </x-label>
                <x-select name="categoria_id" id="categoria_id" class="w-full">
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @if ($categoria->id == old('categoria_id')) selected @endif>
                            {{ $categoria->name }}
                        </option>
                    @endforeach
                </x-select>
                @error('categoria_id')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Subcategoria -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="subcategoria_id" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-tags mr-1"></i>Subcategoría
                </x-label>
                <x-select name="subcategoria_id" id="subcategoria_id" class="w-full">
                    <option value="">Seleccionar subcategoría...</option>
                </x-select>
                @error('subcategoria_id')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>
        </form>
    {{-- </div> --}}
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
    @if (session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "{{ session('success') }}",
                        duration: 4000,
                        close: true,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                        stopOnFocus: true
                    }).showToast();
                }
            @endif
            @if (session('error'))
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "{{ session('error') }}",
                        duration: 4000,
                        close: true,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                        stopOnFocus: true
                    }).showToast();
                }
            @endif
        });
    </script>
    @endif
</x-app-layout>
