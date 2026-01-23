<x-app-layout>
    <x-slot name="header">
        <span class="text-sm text-gray-600">
            <a class="text-blue-600 hover:text-blue-800 underline" href="{{ route('tickets.index') }}">
                <i class="fas fa-ticket-alt mr-1"></i>Tickets
            </a>
            / <span class="font-semibold text-gray-800">Editar</span>
        </span>
        <div class="grid grid-cols-12 gap-2 mb-2 mt-2">
            <!-- Guardar -->
            <div class="col-span-12 lg:col-span-5 flex items-end justify-start space-x-2">
                <button type="button" id="guardar-ticket" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                    <i class="fas fa-save mr-2"></i>Guardar Ticket
                </button>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight mt-1">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>{{ __('EDITAR TICKET') }}
                </h2>
            </div>

        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1 md:p-2 mb-4">
        <form action="{{ route('tickets.update', $ticket->id) }}" method="post" id="ticket-form" class="flex flex-row flex-wrap"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Título -->
            <div class="basis-full p-1 md:p-2">
                <x-label for="titulo" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-heading mr-1"></i>Título
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" placeholder="Título de la solicitud"
                        :value="old('titulo', $ticket->titulo)" />
                @else
                    <p class="text-gray-800 mt-1">{{ $ticket->titulo }}</p>
                @endif
                @error('titulo')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="basis-full p-1 md:p-2">
                <x-label for="descripcion" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-align-left mr-1"></i>Descripción
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-textarea id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" placeholder="Descripción de la solicitud">{{ old('descripcion', $ticket->descripcion) }}</x-textarea>
                @else
                    <p class="text-gray-800 mt-1">{{ $ticket->descripcion }}</p>
                @endif
                @error('descripcion')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Cliente -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="cliente_id" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-building mr-1"></i>Cliente
                </x-label>
                <p class="text-gray-800 mt-1">{{ $ticket->cliente->razon_social }}</p>
            </div>

            <!-- Encargado -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="funcionario_id" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-user-tie mr-1"></i>Encargado
                </x-label>
                @if ($ticket->estado == 'creado' && !$cliente)
                    <x-select name="funcionario_id" id="funcionario_id" class="w-full">
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}" @if ($funcionario->id == $ticket->funcionario_id) selected @endif>
                                ({{ $funcionario->cargo }})
                                {{ $funcionario->user->name }}
                            </option>
                        @endforeach
                    </x-select>
                @else
                    <p class="text-gray-800 mt-1">{{ $ticket->funcionario ? $ticket->funcionario->user->name : 'Sin asignar' }}</p>
                @endif
                @error('funcionario_id')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Tags -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="Tags" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-tag mr-1"></i>Tag
                </x-label>
                <x-select name="tags" id="tags" class="tags form-control w-full">
                    <option value="">Seleccionar tag...</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, $selectags) ? 'selected' : '' }}>
                            {{ $tag->nombre }}
                        </option>
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
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-select name="prioridad" id="prioridad" class="w-full">
                        <option value="urgente" @if ($ticket->prioridad == 'urgente') selected @endif>Urgente</option>
                        <option value="normal" @if ($ticket->prioridad == 'normal') selected @endif>Normal</option>
                    </x-select>
                @else
                    <p class="text-gray-800 mt-1">{{ ucfirst($ticket->prioridad) }}</p>
                @endif
                @error('prioridad')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Nivel SLA -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="nivel_sla" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-exclamation-circle mr-1"></i>Nivel de Soporte (SLA)
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-select name="nivel_sla" id="nivel_sla" class="w-full">
                        <option value="nivel_1" @if ($ticket->nivel_sla == 'nivel_1') selected @endif>Nivel 1</option>
                        <option value="nivel_2" @if ($ticket->nivel_sla == 'nivel_2') selected @endif>Nivel 2</option>
                        <option value="nivel_3" @if ($ticket->nivel_sla == 'nivel_3') selected @endif>Nivel 3</option>
                    </x-select>
                @else
                    <p class="text-gray-800 mt-1">{{ $ticket->nivel_sla ? ucfirst(str_replace('_', ' ', $ticket->nivel_sla)) : 'Nivel 1' }}</p>
                @endif
                @error('nivel_sla')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Tipo -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="tipo" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-list mr-1"></i>Tipo
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-select name="tipo" id="tipo" class="w-full">
                        <option value="soporte" @if ($ticket->tipo == 'soporte') selected @endif>Soporte</option>
                        <option value="ajuste" @if ($ticket->tipo == 'ajuste') selected @endif>Ajuste</option>
                        <option value="desarrollo" @if ($ticket->tipo == 'desarrollo') selected @endif>Desarrollo</option>
                        <option value="capacitacion" @if ($ticket->tipo == 'capacitacion') selected @endif>Capacitacion</option>
                    </x-select>
                @else
                    <p class="text-gray-800 mt-1">{{ ucfirst($ticket->tipo) }}</p>
                @endif
                @error('tipo')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Categoría -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="categoria_id" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-folder-open mr-1"></i>Categoría
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-select name="categoria_id" id="categoria_id" class="w-full">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @if ($categoria->id == $ticket->categoria_id) selected @endif>
                                {{ $categoria->name }}
                            </option>
                        @endforeach
                    </x-select>
                @else
                    <p class="text-gray-800 mt-1">{{ $ticket->categoria ? $ticket->categoria->nombre : 'Sin categoría' }}</p>
                @endif
                @error('categoria_id')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Subcategoría -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="subcategoria_id" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-folder mr-1"></i>Subcategoría
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-select name="subcategoria_id" id="subcategoria_id" class="w-full" data-selected="{{ $ticket->subcategoria_id }}">
                        <option value="">Seleccionar subcategoría...</option>
                    </x-select>
                @else
                    <p class="text-gray-800 mt-1">{{ $ticket->subcategoria ? $ticket->subcategoria->nombre : 'Sin subcategoría' }}</p>
                @endif
                @error('subcategoria_id')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Soportes -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label for="soportes" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-paperclip mr-1"></i>Soportes
                </x-label>
                @if (!$cliente || $ticket->estado == 'creado')
                    <x-input type="file" name="soportes[]" id="soportes" class="block mt-1 w-full" 
                        accept="image/*,.pdf,.doc,.docx" multiple />
                @endif
            </div>

            <!-- Estado -->
            <div class="basis-full md:basis-1/2 lg:basis-1/3 p-1 md:p-2">
                <x-label class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-info-circle mr-1"></i>Estado
                </x-label>
                <input type="hidden" name="estado" id="estado" value="{{ $ticket->estado }}">
                <p class="text-gray-800 mt-1 font-semibold">{{ ucfirst($ticket->estado) }}</p>
            </div>
        </form>
    </div>

    @if($ticket->soportes->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">
                <i class="fas fa-paperclip mr-2 text-blue-600"></i>Soportes Adjuntos
            </h3>
            <div class="grid grid-cols-1 gap-4 w-full">
                @foreach ($ticket->soportes as $soporte)
                    <div x-data="{ expanded: false }" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition w-full">
                        <div class="flex items-center justify-between mb-2">
                            <button type="button" @click="expanded = ! expanded" class="text-blue-600 hover:text-blue-800 font-medium text-sm md:text-base flex items-center">
                                <i class="fas fa-file-alt mr-2"></i>
                                <span>Soporte {{ $loop->iteration }}</span>
                                <i class="fas fa-chevron-down ml-2 transition-transform" :class="{ 'rotate-180': expanded }"></i>
                            </button>
                            @if (!$cliente || $ticket->estado == 'creado')
                                <x-delete-button class="eliminar"
                                    data-form="eliminar-soporte-{{ $soporte->id }}" data-model="Soporte" href="#">
                                </x-delete-button>
                                <x-delete-form id="eliminar-soporte-{{ $soporte->id }}"
                                    action="{{ route('soportes.destroy', $soporte->id) }}">
                                </x-delete-form>
                            @endif
                        </div>
                        <div x-show="expanded" x-collapse class="mt-3 w-full">
                            <img src="/{{ $soporte->ruta }}" alt="Soporte_{{ $soporte->id }}" class="max-w-full w-full h-auto rounded shadow-sm">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
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
