<x-app-layout>
    <x-slot name="header">
        <div class="grid grid-cols-12 mb-2">
            <div class="col-span-12 lg:col-span-6">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-folder-tree mr-2 text-blue-600"></i>{{ __('CATEGORÍAS') }}
                </h2>
                <span class="text-gray-600 text-sm">Gestión de categorías y subcategorías</span>
            </div>
            <div class="col-span-12 lg:col-span-6 flex items-end justify-end space-x-2 mt-2 lg:mt-0">
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Categoría
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <x-toast-notification type="success">
            {{ session('success') }}
        </x-toast-notification>
    @endif

    @if (session('error'))
        <x-toast-notification type="error">
            {{ session('error') }}
        </x-toast-notification>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6">
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">No hay categorías registradas</p>
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center mt-4 px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium rounded shadow-sm transition duration-150 ease-in-out">
                    <i class="fas fa-plus mr-2"></i>
                    Crear primera categoría
                </a>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($categories as $category)
                    <div x-data="{ open: false }" class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-150">
                        <!-- Categoría Principal -->
                        <div class="flex items-start justify-between p-4 bg-white">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                                        <i class="fas fa-folder text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ $category->name }}
                                        </h3>
                                        @if($category->descripcion)
                                            <p class="text-gray-600 text-sm mt-1">{{ $category->descripcion }}</p>
                                        @endif
                                        <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                            <span>
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $category->created_at->format('d/m/Y') }}
                                            </span>
                                            @if($category->children->isNotEmpty())
                                                <button @click="open = !open" 
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition">
                                                    <i class="fas mr-1" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                                    <span x-text="open ? 'Ocultar' : 'Ver'"></span>
                                                    {{ $category->children->count() }} subcategoría{{ $category->children->count() != 1 ? 's' : '' }}
                                                </button>
                                            @else
                                                <span class="text-gray-400">
                                                    <i class="fas fa-layer-group mr-1"></i>
                                                    Sin subcategorías
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('categories.edit', $category) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium rounded transition duration-150">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta categoría? También se eliminarán todas sus subcategorías.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-400 hover:bg-red-500 text-white text-xs font-medium rounded transition duration-150">
                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Acordeón de Subcategorías -->
                        @if($category->children->isNotEmpty())
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="border-t border-gray-200 bg-gray-50"
                                 style="display: none;">
                                <div class="p-4 space-y-2">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 ml-1">
                                        <i class="fas fa-layer-group mr-1"></i>
                                        Subcategorías ({{ $category->children->count() }})
                                    </div>
                                    @foreach($category->children as $subcategory)
                                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-sm transition">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-folder text-blue-500 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-medium text-gray-800 truncate">{{ $subcategory->name }}</h4>
                                                    @if($subcategory->descripcion)
                                                        <p class="text-gray-600 text-xs mt-0.5 truncate">{{ $subcategory->descripcion }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 flex-shrink-0 ml-4">
                                                <a href="{{ route('categories.edit', $subcategory) }}" 
                                                   class="inline-flex items-center px-2 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium rounded transition duration-150"
                                                   title="Editar subcategoría">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('categories.destroy', $subcategory) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar esta subcategoría?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-2 py-1 bg-red-400 hover:bg-red-500 text-white text-xs font-medium rounded transition duration-150"
                                                            title="Eliminar subcategoría">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
