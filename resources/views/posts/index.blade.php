<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/tickets.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="grid grid-cols-3 mb-2">
            <div class="col-start-2">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('INSTRUCTIVOS') }}
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-6 items-center mb-2">
            <div>
                <x-anchor href="{{ route('tickets.create') }}">Crear Ticket</x-anchor>
            </div>
            <div>
                @if (session('success'))
                    <div class="basis-full">
                        <x-small-message class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                            {{ session('success') }}
                        </x-small-message>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

</x-app-layout>
