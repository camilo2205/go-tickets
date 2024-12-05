<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('css/cruds/bitacora.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('proyectos.index') }}">Proyectos</a>
                    / <span class="font-bold">Ver</span>
                </span>
            </div>
            <div class="basis-2/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('PROYECTO') }} #{{ str_pad($proyecto->id, 5, '0', STR_PAD_LEFT) }}
                    ({{ formatDate($proyecto->created_at, 'd/m/Y h:i A') }})
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

    <table class="border-collapse border border-slate-400 w-full">
        <tr>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Proyecto: </strong><br>
                {{ $proyecto->nombre }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Cliente: </strong><br>
                {{ $proyecto->cliente->razon_social }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Estado: </strong><br>
                {{ ucfirst($proyecto->estado) }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Progreso: </strong><br>
                {{ $proyecto->progreso }}%
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $proyecto->progreso }}%;"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="4">
                <strong>Descripción: </strong><br>
                <div class="descripcion ver">{!! $proyecto->descripcion !!}</div>
            </td>
        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="4" id="td-bitacora">
                @foreach ($proyecto->bitacora as $registro)
                <div class="flex {{ $registro->user->cliente ? 'flex-row' : 'flex-row-reverse' }} space-x-2">
                    <div @if ($registro->visto == 0)
                        class="rounded-xl m-1 p-3 basis-7/12 {{ $registro->user->cliente ? 'bg-cyan-300' :
                        'bg-green-200' }}">
                        <strong>{{ $registro->user->name }}
                            ({{ $registro->user->cliente ? 'Cliente' : ($registro->user->funcionario ? 'Funcionario' :
                            'Admin') }})
                            - {{ formatDate($registro->created_at, 'd/m/Y h:i A') }}
                            {{ $registro->cerrar ? '(Cerrado)' : '' }}
                        </strong><i class="fa-solid fa-check-double"></i><br>
                        {!! nl2br(e($registro->cuerpo))!!}
                        @else
                        class="rounded-xl m-1 p-3 basis-7/12 {{ $registro->user->cliente ? 'bg-cyan-300' :
                        'bg-green-200' }}">
                        <strong>{{ $registro->user->name }}
                            ({{ $registro->user->cliente ? 'Cliente' : ($registro->user->funcionario ? 'Funcionario' :
                            'Admin') }})
                            - {{ formatDate($registro->created_at, 'd/m/Y h:i A') }}
                            {{ $registro->cerrar ? '(Cerrado)' : '' }}
                        </strong><i class="fa-solid fa-check-double text-blue-600"></i><br>
                        {!! nl2br(e($registro->cuerpo))!!}
                        @endif
                    </div>
                </div>
                @endforeach
            </td>
        </tr>
    </table>
</x-app-layout>