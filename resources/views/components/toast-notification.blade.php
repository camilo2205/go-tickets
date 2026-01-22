@props(['type' => 'success'])

@php
    $types = [
        'success' => 'bg-green-500 text-white',
        'error' => 'bg-red-500 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'info' => 'bg-blue-500 text-white',
    ];
    
    $icons = [
        'success' => 'fa-check-circle',
        'error' => 'fa-times-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
    ];
    
    $bgClass = $types[$type] ?? $types['success'];
    $icon = $icons[$type] ?? $icons['success'];
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 4000)"
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full {{ $bgClass }} shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
     style="display: none;">
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas {{ $icon }} text-xl"></i>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium">
                    {{ $slot }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" 
                        class="inline-flex text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                    <span class="sr-only">Cerrar</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
