<div class="max-w-lg mx-auto">
    <div class="bg-white shadow-md border border-gray-200 rounded-lg max-w-sm mb-5">
        @if (isset($img))
            <a href="{{ $url }}">
                <img class="rounded-t-lg" src="{{ $img }}" alt="">
            </a>
        @endif
        <div class="p-5">
            <a href="{{ $url }}">
                <h5 class="text-center text-gray-900 font-bold text-2xl tracking-tight mb-2 titulo">
                    {{ $title }}
                </h5>
            </a>
            <p class="font-normal text-gray-700 mb-3 text-justify cuerpo">
                {{ $slot }}
            </p>
            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center boton"
                href="{{ $url }}">
                Leer más...
            </a>
        </div>
    </div>
</div>
