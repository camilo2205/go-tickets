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
            @if (session('success'))
                <div class="basis-1/3 self-start">
                    <div class="basis-full">
                        <x-small-message class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                            {{ session('success') }}
                        </x-small-message>
                    </div>
                </div>
            @endif
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

        <table class="border-collapse border border-slate-400 w-full">
            <tr>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Cliente: </strong><br>
                    {{ $ticket->cliente->razon_social }}
                </td>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Encargado: </strong><br>
                    @if ($ticket->estado == 'creado' && !$cliente)
                        <x-select name="funcionario_id" id="funcionario_id">
                            @foreach ($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}"
                                    @if ($funcionario->id == $ticket->funcionario_id) selected @endif>
                                    {{ $funcionario->user->name }}
                                </option>
                            @endforeach
                        </x-select>
                    @else
                        {{ $ticket->funcionario ? $ticket->funcionario->user->name : 'Sin asignar' }}
                    @endif
                    @error('funcionario_id')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
                <td class="border border-slate-300 px-5 py-1">
                    <strong>Tipo: </strong><br>
                    @if (!$cliente || $ticket->estado == 'creado')
                        <x-select name="tipo" id="tipo">
                            <option value="soporte" @if ($ticket->tipo == 'soporte') selected @endif>Soporte</option>
                            <option value="ajuste" @if ($ticket->tipo == 'ajuste') selected @endif>Ajuste</option>
                            <option value="desarrollo" @if ($ticket->tipo == 'desarrollo') selected @endif>Desarrollo
                            </option>
                            <option value="capacitacion" @if ($ticket->tipo == 'capacitacion') selected @endif>Capacitacion
                            </option>
                        </x-select>
                    @else
                        {{ ucfirst($ticket->estado) }}
                    @endif
                    @error('tipo')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
                <td class="border border-slate-300 px-5 py-1" colspan="3">
                    <strong>Prioridad: </strong><br>
                    @if (!$cliente || $ticket->estado == 'creado')
                        <x-select name="prioridad" id="prioridad">
                            <option value="normal" @if ($ticket->prioridad == 'normal') selected @endif>Normal</option>
                            <option value="urgente" @if ($ticket->prioridad == 'urgente') selected @endif>Urgente</option>
                        </x-select>
                    @else
                        {{ ucfirst($ticket->prioridad) }}
                    @endif
                    @error('prioridad')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
            </tr>
            <tr>
                <td class="border border-slate-300 px-5 py-1" colspan="6">
                    <strong>Descripción: </strong><br>
                    @if (!$cliente || $ticket->estado == 'creado')
                        <x-textarea id="descripcion" class="block mt-1 w-full" type="text" name="descripcion"
                            :value="old('descripcion')">
                            {{ $ticket->descripcion }}
                        </x-textarea>
                    @else
                        <p style="font-size: 12px">{{ $ticket->descripcion }}</p>
                    @endif
                    @error('descripcion')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
            </tr>
            <tr>
                <td class="border border-slate-300 px-5 py-1" colspan="1">
                    @if (!$cliente || $ticket->estado == 'creado')
                        <x-input type="file" name="soportes[]" id="soportes" class="block mt-1 w-full"
                            accept="image/*" multiple />
                    @endif
                </td>
                <td class="border border-slate-300 px-5 py-1" colspan="3">
                    <strong>Tags</strong><br>
                    <x-select multiple="multiple" name="tags[]" id="tags" class="tags form-control">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ in_array($tag->id, $selectags) ? 'selected' : '' }}>
                                {{ $tag->nombre }} </option>
                        @endforeach
                    </x-select>
                    @error('tags')
                        <x-small>{{ $message }}</x-small>
                    @enderror
                </td>
                <td class="border border-slate-300 px-5 py-1" colspan="2">
                    <input type="hidden" name="estado" id="estado" value="creado">
                    <strong>Estado:</strong> {{ ucfirst($ticket->estado) }}
                </td>
            </tr>
        </table>
    </form>
    <table class="w-full">
        @foreach ($ticket->soportes as $soporte)
            <tr x-data="{ expanded: false }" class="py-1">
                <td class="border border-slate-300 py-1 px-5">
                    <button type="button" @click="expanded = ! expanded" class="basis-11/12 ml-2 my-1">
                        Soporte {{ $loop->iteration }}
                    </button>
                    @if (!$cliente || $ticket->estado == 'creado')
                        <x-delete-button class="basis-1/2 eliminar ml-2"
                            data-form="eliminar-soporte-{{ $soporte->id }}" data-model="Soporte" href="#">
                        </x-delete-button>
                        <x-delete-form id="eliminar-soporte-{{ $soporte->id }}"
                            action="{{ route('soportes.destroy', $soporte->id) }}">
                        </x-delete-form>
                    @endif
                    <p x-show="expanded" x-collapse>
                        <img src="/{{ $soporte->ruta }}" alt="Soporte_{{ $soporte->id }}" class="mt-2">
                    </p>
                    <br>
                </td>
            </tr>
        @endforeach
    </table>
    <script src="{{ asset('js/cruds/tickets.js') }}" defer></script>
</x-app-layout>
