<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('tickets.index') }}">Tickets</a>
                    / <span class="font-bold">Editar</span>
                </span>
            </div>
            <div class="basis-1/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('EDITAR TICKET') }}
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

    <form action="{{ route('tickets.update', $ticket->id) }}" method="post" class="flex flex-row flex-wrap space-y-4"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Guardar -->
        <div class="basis-full px-2">
            <x-button>Guardar</x-button>
        </div>

        <table class="border-collapse border border-slate-400">
            <tr>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Cliente: </strong><br>
                    {{ $ticket->cliente->razon_social }}
                </td>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Asignar: </strong><br>
                    <x-select name="funcionario_id" id="funcionario_id">
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}" @if ($funcionario->id == $ticket->funcionario_id) selected @endif>
                                {{ $funcionario->user->name }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('funcionario_id')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Tipo: </strong><br>
                    <x-select name="tipo" id="tipo">
                        <option value="soporte" @if ($ticket->tipo == 'soporte') selected @endif>Soporte</option>
                        <option value="ajuste" @if ($ticket->tipo == 'ajuste') selected @endif>Ajuste</option>
                        <option value="desarrollo" @if ($ticket->tipo == 'desarrollo') selected @endif>Desarrollo</option>
                        <option value="capacitacion" @if ($ticket->tipo == 'capacitacion') selected @endif>Capacitacion
                        </option>
                    </x-select>
                    @error('tipo')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Prioridad: </strong><br>
                    <x-select name="prioridad" id="prioridad">
                        <option value="normal" @if ($ticket->prioridad == 'normal') selected @endif>Normal</option>
                        <option value="urgente" @if ($ticket->prioridad == 'urgente') selected @endif>Urgente</option>
                    </x-select>
                    @error('prioridad')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
            </tr>
            <tr>
                <td class="border border-slate-300 px-5 py-1" colspan="4">
                    <strong>Descripción: </strong><br>
                    <x-textarea id="descripcion" class="block mt-1 w-full" type="text" name="descripcion"
                        :value="old('descripcion')">
                        {{ $ticket->descripcion }}
                    </x-textarea>
                    @error('descripcion')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
            </tr>
            <tr>
                <td class="border border-slate-300 px-5 py-1" colspan="4">
                    <strong>Soportes: </strong><br>
                    @foreach ($ticket->soportes as $soporte)
                        <table class="w-full border-collapse border border-slate-400">
                            <tr x-data="{ expanded: false }" class="py-1">
                                <td class="border border-slate-300">
                                    <button type="button" @click="expanded = ! expanded" class="w-full ml-2">Soporte
                                        {{ $loop->iteration }}</button>
                                    <p x-show="expanded" x-collapse>
                                        <img src="/{{ $soporte->ruta }}" alt="Soporte_{{ $soporte->id }}"
                                            class="mt-2">
                                    </p>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </td>
            </tr>
        </table>
    </form>
</x-app-layout>
