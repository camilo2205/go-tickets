<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <x-anchor href="{{ route('users.create') }}">Agregar</x-anchor>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('Usuarios') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <table class="border-collapse border border-slate-400">
        <thead>
            <tr>
                <th class="border border-slate-300 px-5 py-1">Identificación</th>
                <th class="border border-slate-300 px-5 py-1">Nombre</th>
                <th class="border border-slate-300 px-5 py-1">Correo</th>
                <th class="border border-slate-300 px-5 py-1">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td nowrap class="border border-slate-300 px-5 py-1">{{ $user->identificacion }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $user->name }}</td>
                    <td class="border border-slate-300 px-5 py-1">{{ $user->email }}</td>
                    <td class="border border-slate-300 px-5 py-1">
                        <div class="flex flex-row space-x-2">
                            <x-edit-button class="basis-1/2" href="{{ route('users.edit', $user->id) }}">
                            </x-edit-button>
                            <x-delete-button class="basis-1/2"
                                href="{{ route('users.destroy', $user->id) }}"></x-delete-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
