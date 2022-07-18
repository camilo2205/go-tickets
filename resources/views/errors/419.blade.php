<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/cruds/clientes.css') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            ERROR 419: {{ __('La sesión ha expirado.') }}
        </h2>
    </x-slot>
    Haga clíck <a href="{{ route('dashboard') }}" class="text-blue-500">aquí</a> para volver al inicio.
</x-app-layout>
