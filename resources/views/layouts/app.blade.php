<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased">
    @include('layouts.navigation')
    <div class="min-h-screen bg-white-100">
        <!-- Page Content -->
        <main>
            <div class="flex flex-col md:flex-row">
                @include('layouts.sidebar')
                <section class="overflow-auto mt-16">
                    <div id="main" class="main-content flex-1 bg-white-100 mt-12 md:mt-2 pb-24 md:pb-5">
                        <!-- Page Heading -->
                        <header class="bg-white shadow">
                            <div class="mx-auto py-2 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>

                        <div class="mx-auto sm:px-6 lg:px-8 pt-3 flex-1 w-full">
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
</body>

</html>
