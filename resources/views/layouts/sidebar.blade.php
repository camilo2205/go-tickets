<nav aria-label="alternative nav">
    <div class="bg-white h-20 fixed bottom-0 md:relative md:h-screen w-full md:w-48 content-center">
        <div
            class="md:mt-16 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
            <ul class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                @can('dashboard')
                    <li class="mr-3 flex-1">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Inicio') }}
                        </x-nav-link>
                    </li>
                @endcan
                @can('clientes.index')
                    <li class="mr-3 flex-1">
                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                            {{ __('Clientes') }}
                        </x-nav-link>
                    </li>
                @endcan
                @can('funcionarios.index')
                    <li class="mr-3 flex-1">
                        <x-nav-link :href="route('funcionarios.index')" :active="request()->routeIs('funcionarios.*')">
                            {{ __('Funcionarios') }}
                        </x-nav-link>
                    </li>
                @endcan
                @can('tickets.index')
                    <li class="mr-3 flex-1">
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                            {{ __('Tickets') }}
                        </x-nav-link>
                    </li>
                @endcan
                @can('users.index')
                    <li class="mr-3 flex-1">
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Usuarios') }}
                        </x-nav-link>
                    </li>
                @endcan
                <li class="mr-3 flex-1">
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
                        {{ __('Instructivos') }}
                    </x-nav-link>
                </li>
                @can('bitacora.index')
                    <li class="mr-3 flex-1">
                        <x-nav-link :href="route('bitacora.index')" :active="request()->routeIs('bitacora.*')">
                            {{ __('Bitacora') }}
                        </x-nav-link>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</nav>
