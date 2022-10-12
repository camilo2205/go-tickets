<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row content-end">
            <div class="basis-2/3">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ $post->title }}
                </h2>
            </div>
        </div>
    </x-slot>

    {!! $post->body !!}
</x-app-layout>
