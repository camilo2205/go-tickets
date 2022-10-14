<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('/css/posts.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="grid grid-cols-3 mb-2">
            <div class="col-start-2">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('INSTRUCTIVOS') }}
                </h2>
            </div>
        </div>
        @if (auth()->user()->hasRole('superadmin'))
            <div class="grid grid-cols-12 items-center mb-2">
                <div>
                    <x-anchor target="_blank" href="/wink">Panel de Instructivos</x-anchor>
                </div>
                <div>
                    @if (session('success'))
                        <div class="basis-full">
                            <x-small-message
                                class="bg-green-200 text-green-600 text-center w-full p-1 rounded font-bold">
                                {{ session('success') }}
                            </x-small-message>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </x-slot>

    <div class="grid grid-cols-3 gap-3">
        @foreach ($posts as $post)
            <x-card url="/posts/{{ $post->slug }}" title="{{ $post->title }}" img="{{ $post->featured_image }}">
                {{ substr(strip_tags($post->content), 0, 100) }}...
            </x-card>
        @endforeach
    </div>
</x-app-layout>
