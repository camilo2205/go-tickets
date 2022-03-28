<nav aria-label="alternative nav">
    <div class="bg-white-800 shadow-xl h-20 fixed bottom-0 md:relative md:h-screen z-10 w-full md:w-48 content-center">
        <div
            class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
            <ul class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                <li class="mr-3 flex-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Inicio') }}
                    </x-nav-link>
                </li>
                <li class="mr-3 flex-1">
                    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                        {{ __('Clientes') }}
                    </x-nav-link>
                </li>
                <li class="mr-3 flex-1">
                    <x-nav-link :href="route('encargados.index')" :active="request()->routeIs('encargados.*')">
                        {{ __('Encargados') }}
                    </x-nav-link>
                </li>
                <li class="mr-3 flex-1">
                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                        {{ __('Tickets') }}
                    </x-nav-link>
                </li>
                <li class="mr-3 flex-1">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Usuarios') }}
                    </x-nav-link>
                </li>
            </ul>
        </div>
    </div>
</nav>
