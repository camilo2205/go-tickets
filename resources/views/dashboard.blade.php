<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-2/5 self-end">
                <h2 class="font-semibold text-right text-2xl text-gray-800 leading-tight">
                    {{ __('INICIO') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-row flex-wrap">
        <div class="md:basis-5/12 basis-full px-5">
            <canvas id="grafico"></canvas>
        </div>
        <div class="md:basis-1/2 basis-full px-5">
            <canvas id="grafico2"></canvas>
        </div>
    </div>

    <div class="flex flex-row flex-wrap">
        <div class="md:basis-11/12 basis-full px-5">
            <canvas id="ticketsxcliente" height="300vh"></canvas>
        </div>
    </div>

    <script src="/js/dashboard.js"></script>
</x-app-layout>
