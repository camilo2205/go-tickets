<x-app-layout>
    <x-slot name="header">
        <span class="text-sm text-gray-600">
            <a class="text-blue-600 hover:text-blue-800 underline" href="{{ route('categories.index') }}">
                <i class="fas fa-folder-tree mr-1"></i>Categorías
            </a>
            / <span class="font-semibold text-gray-800">Crear</span>
        </span>
        <div class="grid grid-cols-12 mt-2 mb-2">
            <div class="col-span-12 lg:col-span-6">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight mt-1">
                    {{ __('CREAR CATEGORÍA') }}<i class="fas fa-plus-circle ml-2 text-blue-600"></i>
                </h2>
            </div>
        </div>
    </x-slot>

    @if (session('error'))
        <x-toast-notification type="error">
            {{ session('error') }}
        </x-toast-notification>
    @endif

    @if ($errors->any())
        <x-toast-notification type="error">
            Por favor, corrige los errores en el formulario.
        </x-toast-notification>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6">
        <form action="{{ route('categories.store') }}" method="POST" class="flex flex-row flex-wrap">
            @csrf

            <!-- Nombre de la Categoría -->
            <div class="basis-full p-1 md:p-2">
                <x-label for="name" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-tag mr-1"></i>Nombre de la Categoría *
                </x-label>
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" 
                    placeholder="Nombre de la categoría" :value="old('name')" required autofocus />
                @error('name')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="basis-full p-1 md:p-2">
                <x-label for="descripcion" class="text-blue-900 font-semibold mb-1">
                    <i class="fas fa-align-left mr-1"></i>Descripción
                </x-label>
                <x-textarea id="descripcion" class="block mt-1 w-full" name="descripcion" 
                    placeholder="Descripción opcional de la categoría" rows="3">{{ old('descripcion') }}</x-textarea>
                @error('descripcion')
                    <x-small class="text-red-600">{{ $message }}</x-small>
                @enderror
            </div>

            <!-- Sección de Subcategorías (solo para categorías principales) -->
            <div class="basis-full p-1 md:p-2 mt-4" id="subcategories-section">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                                Subcategorías
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Añade subcategorías a esta categoría principal
                                <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    <span id="subcategory-count">0</span> subcategorías
                                </span>
                            </p>
                        </div>
                        <button type="button" id="add-subcategory-btn" 
                                class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Añadir Subcategoría
                        </button>
                    </div>

                    <div id="subcategories-list" class="space-y-3">
                        <!-- Las subcategorías se añadirán aquí dinámicamente -->
                    </div>

                    <div id="subcategories-empty-message" class="text-center py-8 text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-2 opacity-50"></i>
                        <p>No hay subcategorías añadidas aún</p>
                        <p class="text-sm">Haz clic en "Añadir Subcategoría" para comenzar</p>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="basis-full p-1 md:p-2 mt-4 flex justify-between items-center border-t pt-4">
                <a href="{{ route('categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                    <i class="fas fa-save mr-2"></i>Guardar Categoría
                </button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('js/cruds/categories.js') }}"></script>
</x-app-layout>
