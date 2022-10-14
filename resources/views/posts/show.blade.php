<x-app-layout>
    <x-slot name='styles'>
        <link rel="stylesheet" href="{{ asset('/css/posts.css') }}">
    </x-slot>
    <x-slot name="header">
        <div class="basis-1/3">
            <span class="inline-flex text-sm text-gray-800">
                <a class="underline" href="{{ route('posts.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
            </span>
        </div>
        <div class="titulo content-end">
            <div class="basis-2/3">
                <h1 class="text-center font-semibold text-3xl text-gray-800 leading-tight">
                    {{ $post->title }}
                </h1>
            </div>
        </div>
    </x-slot>
    <!-- component -->
    <div class="px-2 md:px-6 my-3 w-full text-slate-700 dark:text-white flex flex-col items-center">
        <div class="w-full rounded-xl flex-col xl:flex-row bg-white dark:bg-slate-900 shadow-md">
            <div class="rounded-t-xl w-full h-64 shadow-sm bg-cover"
                style='background-image: url("{{ $post->featured_image }}");'></div>

            <div class="w-full p-3 flex flex-col justify-between h-auto overflow-auto lg:h-auto">
                <p class="text-sm">{!! $post->content !!}</p>
            </div>
        </div>
    </div>
</x-app-layout>
