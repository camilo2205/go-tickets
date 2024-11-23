<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('img/gomedical.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/gomedical.png') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{ isset($styles) ? $styles : '' }}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link href="{{ asset('librerias/fancyboxui.min.css') }}" rel="stylesheet">
    <script src="{{ asset('librerias/fancyboxui.umd.js') }}"></script>
</head>

<body class="font-sans antialiased">
    @auth
        @include('layouts.navigation')
    @endauth
    <div class="min-h-screen bg-white">
        <!-- Page Content -->
        <main>
            <div class="flex flex-col md:flex-row">
                @auth
                    @include('layouts.sidebar')
                @endauth
                <section class="overflow-auto mt-16 w-full">
                    <div id="main" class="main-content flex-1 bg-white mt-12 md:mt-3 pb-24 md:pb-5">
                        <!-- Page Heading -->
                        <header class="bg-white">
                            <div class="mx-auto py-2 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>

                        <div class="mx-auto flex-1 w-full">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="px-6 bg-white border-b border-gray-200 overflow-x-auto pb-2">
                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
   <!-- Configura las variables globales ANTES de app.js -->
   <script>
    window.laravelEchoConfig = {
        key: '{{ config("broadcasting.connections.pusher.key") }}',
        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
        wsHost: window.location.hostname,
        wsPort: {{ config('broadcasting.connections.pusher.options.port', 6001) }},
        wssPort: {{ config('broadcasting.connections.pusher.options.port', 6001) }},
        forceTLS: false,
        enabledTransports: ['ws', 'wss']
    };
</script>
    @auth
        <script src="{{ asset('js/enable-push.js') }}" defer></script>
    @endauth
</body>

</html>
