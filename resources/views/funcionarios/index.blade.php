<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/cruds/funcionarios.css') }}">
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <x-anchor href="{{ route('funcionarios.create') }}">Agregar</x-anchor>
            </div>
            <div class="basis-1/4 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('FUNCIONARIOS') }}
                </h2>
            </div>
            <div class="basis-1/3 self-start">
                @if (session('success'))
                    <div class="basis-full">
                        <x-small-message class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                            {{ session('success') }}</x-small-message>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <table class="border-collapse border border-slate-400">
        <thead>
            <tr>
                <th class="border border-slate-300 px-5 py-1">Acciones</th>
                <th class="border border-slate-300 px-5 py-1">Identificación</th>
                <th class="border border-slate-300 px-5 py-1">Nombre</th>
                <th class="border border-slate-300 px-5 py-1">Cargo</th>
                <th class="border border-slate-300 px-5 py-1">Telefono</th>
                <th class="border border-slate-300 px-5 py-1">Celular</th>
                <th class="border border-slate-300 px-5 py-1">Correo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($funcionarios as $cliente)
                <tr>
                    <td class="border border-slate-300 px-5 py-2">
                        <div class="flex flex-row space-x-2">
                            <x-edit-button class="basis-1/2" href="{{ route('funcionarios.edit', $cliente->id) }}">
                            </x-edit-button>
                            <x-delete-button class="basis-1/2 eliminar" data-form="eliminar-cliente"
                                data-model="Cliente" href="#">
                            </x-delete-button>
                            <x-delete-form id="eliminar-cliente"
                                action="{{ route('funcionarios.destroy', $cliente->id) }}"></x-delete-form>
                        </div>
                    </td>
                    <td nowrap class="border border-slate-300 px-5 py-1">{{ $cliente->user->identificacion }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $cliente->user->name }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $cliente->cargo }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $cliente->user->telefono }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $cliente->user->celular }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $cliente->user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/cruds/funcionarios.js') }}" defer></script>
</x-app-layout>
