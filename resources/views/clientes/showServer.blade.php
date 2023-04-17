<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-1/3">
                <span class="text-sm text-gray-800"><a class="underline" href="{{ route('clientes.index') }}">Clientes</a>
                    / <span class="font-bold">Ver</span>
                </span>
            </div>
            <div class="basis-2/3 self-end">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('SERVIDOR') }} #{{ str_pad($server ? $server->id : '', 5, '0', STR_PAD_LEFT) }}
                    ({{ formatDate($server ? $server->created_at : '', 'd/m/Y h:i A') }})
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
                <strong>Nombre servidor: </strong><br>
                {{ $server ? $server->nombre : 'Sin registro' }}
            </td>
            <td class="border border-slate-300 px-5 py-1">
                @if ($server)
                    @if ($server->disk_capacidad / 1073741824 > 1)
                        <strong>Capacidad total: </strong> <br>{{ round($server->disk_capacidad / 1073741824, 2) }} TB
                    @elseif ($server->disk_capacidad / 1048576 > 1)
                        <strong>Capacidad total: </strong> <br>{{ round($server->disk_capacidad / 1048576, 2) }} GB
                    @elseif ($server->disk_capacidad / 1024 > 1)
                        <strong>Capacidad total: </strong> <br>{{ round($server->disk_capacidad / 1024, 2) }} MB
                    @else
                        <strong>Capacidad total: </strong><br> {{ round($server->disk_capacidad , 2) }} KB
                    @endif
                @else
                    <strong>Capacidad total: </strong> <br>Sin registro
                @endif
            </td>
            <td class="border border-slate-300 px-5 py-1">
                <strong>Cliente</strong><br>
                {{ $cliente->razon_social }}
            </td>

        </tr>
        <tr>
            <td class="border border-slate-300 px-5 py-1" colspan="3">
                <div class="mb-2"><strong>Discos: </strong></div>
                @if ($server)
                    @foreach ($server->disks as $disk)
                        <div x-data="{ open: false }" class="w-[100] mx-auto bg-gray-50 border-b border-gray-300">
                            <div @click="open=!open"
                                :class="{
                                    'bg-red-100 rounded-md': {{ $disk->used }} >= 90,
                                    'bg-yellow-100 rounded-md': {{ $disk->used }} >= 60 && {{ $disk->used }} < 90,
                                    'bg-green-100 rounded-md': {{ $disk->used }} < 60
                                }"
                                class="flex justify-between items-center cursor-pointer">
                                <p class="px-4">{{ $disk->mounted }}</p>
                                <button @click.stop='open=!open' x-html="open ? '-' :'+' "
                                    class="px-2 text-black hover:text-gray-500 font-bold text-3xl"></button>
                            </div>
                            <div x-show="open" x-cloak class="mx-4 py-4" x-transition>
                                <ul class="w-max flex flex-col ">
                                    <li
                                        class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-device-hdd" viewBox="0 0 16 16">
                                            <path
                                                d="M12 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm0 11a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm-7.5.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM5 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM8 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                                            <path
                                                d="M12 7a4 4 0 0 1-3.937 4c-.537.813-1.02 1.515-1.181 1.677a1.102 1.102 0 0 1-1.56-1.559c.1-.098.396-.314.795-.588A4 4 0 0 1 8 3a4 4 0 0 1 4 4Zm-1 0a3 3 0 1 0-3.891 2.865c.667-.44 1.396-.91 1.955-1.268.224-.144.483.115.34.34l-.62.96A3.001 3.001 0 0 0 11 7Z" />
                                            <path
                                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4Z" />
                                        </svg>
                                        @if ($disk->capacity / 1073741824 > 1)
                                            <strong>Capacidad: </strong>{{ round($disk->capacity / 1073741824, 2) }} TB
                                        @elseif ($disk->capacity / 1048576 > 1)
                                            <strong>Capacidad: </strong>{{ round($disk->capacity / 1048576 , 2) }} GB
                                        @elseif ($disk->capacity / 1024 > 1)
                                            <strong>Capacidad: </strong>{{ round($disk->capacity / 1024, 2) }} MB
                                        @else
                                            <strong>Capacidad: </strong>{{ round($disk->capacity, 2) }} KB
                                        @endif
                                    </li>
                                    <li
                                        class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium  bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-device-ssd" viewBox="0 0 16 16">
                                            <path
                                                d="M4.75 4a.75.75 0 0 0-.75.75v3.5c0 .414.336.75.75.75h6.5a.75.75 0 0 0 .75-.75v-3.5a.75.75 0 0 0-.75-.75h-6.5ZM5 8V5h6v3H5Zm0-5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm7 0a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM4.5 11a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Zm7 0a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Z" />
                                            <path
                                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2Zm11 12V2a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1v-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2a1 1 0 0 0 1-1Zm-7.25 1v-2H5v2h.75Zm1.75 0v-2h-.75v2h.75Zm1.75 0v-2H8.5v2h.75ZM11 13h-.75v2H11v-2Z" />
                                        </svg>
                                        <strong>Usado: </strong>{{ $disk->used }}%
                                    </li>
                                    <li
                                        class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium  bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-calendar-date" viewBox="0 0 16 16">
                                            <path
                                                d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z" />
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                        </svg>
                                        <strong>Fecha actualización:
                                        </strong>{{ formatDate($disk->updated_at, 'd/m/Y h:i A') }}
                                    </li>
                                    <li
                                        class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                        <div class="flex items-center">
                                            <input id="default-checkbox" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                data-id="{{ $disk->id }}"
                                                {{ $disk->notificable === true ? 'checked' : ' ' }}>
                                            <label for="default-checkbox"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Notificable</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
    <script src="{{ asset('js/cruds/clientes.js') }}" defer></script>
    <script>
        let cliente = "@json($cliente->id)"
    </script>
</x-app-layout>
