<div>
    {{-- LOGO --}}
    <div @click.away="open = false"
        class="flex flex-col flex-shrink-0 w-full text-gray-700 bg-white h-full md:w-60 dark-mode:text-gray-200 dark-mode:bg-gray-800"
        x-data="{ open: false }">

        <div>
            <p class="font-bold text-sm mt-2 pl-5">
                @if (auth()->user()->roles != null)
                    <i class="fas fa-user mr-2 text-green-800"></i>
                    {{ auth()->user()->name }} ({{ auth()->user()->roles->pluck('name')->implode(', ') }})
                @endif
            </p>
        </div>

        <div class="flex flex-row items-center justify-between md:justify-center flex-shrink-0 px-2 py-4">
            <a href="{{ route('dashboard') }}"
                class="text-3xl font-extrabold text-gray-900 uppercase rounded-lg dark-mode:text-white focus:outline-none focus:shadow-outline text-center">
                <div class="flex items-center justify-center">
                    {{-- <img src="{{ asset('img/logo2.png') }}" class="h-32" alt="Logo"> --}}
                    <img src="{{ asset('img/logo.png') }}" class="" alt="Logo">

                </div>
            </a>
            <br>
            <br><br>
            <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                    <path x-show="!open" fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                    <path x-show="open" fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        {{-- NAVBAR --}}
        <nav :class="{ 'block': open, 'hidden': !open }" class="flex-grow px-4 pb-4 md:block md:pb-0 overflow-auto">

            @can('admin.notifications.index')
                {{-- SUBTÍTULO NOTIFICACIONES --}}
                <span class="font-bold">Notificaciones
                    <hr>
                </span>

                <a href="{{ route('admin.notifications.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <div class="flex items-center">
                        <i class="fas fa-bell mr-2"></i>
                        <div class="w-full flex justify-between items-center">
                            <span class="w-full">Notificaciones</span>
                            <span
                                class="font-bold {{ auth()->user()->unreadNotifications->count() > 0? 'bg-sky-50': 'bg-gray-200' }} px-3 py-1 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        </div>
                    </div>
                    <br>
                </a>
            @endcan

            {{-- SUBTÍTULO COMPRAS --}}
            @can('admin.suppliers.index')
                <span class="font-bold">Compras a proveedores</span>
                <hr>

                <a href="{{ route('admin.suppliers.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-user-friends mr-2"></i>
                    Proveedores
                </a>

                {{-- APARTADO VENTAS --}}
                <div @click.away="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex flex-row items-center w-full px-4 py-1 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 md:block hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Compras
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }"
                            class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="right-0 w-full mt-2 origin-top-right rounded-md shadow-lg">
                        <div class="px-2 py-1 bg-white rounded-md shadow dark-mode:bg-gray-700">
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.purchases.index') }}">Compras</a>
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.purchases.create') }}">Nueva compra</a>
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.purchase-orders.index') }}">Órdenes de compra</a>
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.purchase-orders.create') }}">Nueva orden de compra</a>
                        </div>
                    </div>
                </div>
                <br>
            @endcan

            @can('admin.sales.index')
                {{-- SUBTÍTULO VENTAS --}}
                <span class="font-bold">Ventas a clientes</span>
                <hr>

                <a href="{{ route('admin.clients.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-user-friends mr-2"></i>
                    Clientes
                </a>

                {{-- APARTADO VENTAS --}}
                <div @click.away="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex flex-row items-center w-full px-4 py-1 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 md:block hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                        <i class="fas fa-business-time mr-2"></i>
                        Ventas
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }"
                            class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="right-0 w-full mt-2 origin-top-right rounded-md shadow-lg">
                        <div class="px-2 py-1 bg-white rounded-md shadow dark-mode:bg-gray-700">
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.sales.index') }}">Ventas</a>
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.sales.create') }}">Nueva venta</a>
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.sale-orders.index') }}">Órdenes de venta</a>
                            <a class="block px-4 py-1 mt-2 text-sm font-semibold bg-transparent rounded-lg dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                                href="{{ route('admin.sale-orders.create') }}">Nueva orden de venta</a>
                        </div>
                    </div>
                </div>
                <br>
            @endcan

            {{-- SUBTÍTULO PRODUCTOS --}}
            @can('admin.products.index', 'admin.tasks.index', 'admin.lots.index')
                <span class="font-bold">Producción</span>
                <hr>
            @endcan

            @can('admin.products.index')
                <a href="{{ route('admin.products.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-tree mr-2"></i>
                    Productos
                </a>
            @endcan

            @can('admin.trunk-lots.index')
                <a href="{{ route('admin.trunk-lots.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-chart-area mr-2"></i>
                    Playa de rollos
                </a>
            @endcan

            @can('admin.necessary-production.index')
                <a href="{{ route('admin.necessary-production.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-link mr-2"></i>
                    Producción necesaria
                </a>
            @endcan

            @can('admin.tasks.index')
                <a href="{{ route('admin.tasks.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-tasks mr-2"></i>
                    Tareas
                </a>
            @endcan

            @can('admin.lots.index')
                <a href="{{ route('admin.lots.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-box-tissue mr-2"></i>
                    Lotes y sublotes
                </a>
            @endcan

            @can('admin.sublots-areas.index')
                <a href="{{ route('admin.sublots-areas.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Sublotes por área
                </a>
            @endcan

            @can('admin.sublots-products.index')
                <a href="{{ route('admin.sublots-products.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-search-location mr-2"></i>
                    Sublotes por producto
                </a>
            @endcan

            @can('admin.tasks.report')
                <a href="{{ route('admin.tasks.report') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Reporte de tareas
                </a>
            @endcan

            @can('admin.stadistics.index')
                <a href="{{ route('admin.stadistics.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-chart-line mr-2"></i>
                    Estadísticas
                </a>
            @endcan

            @can('admin.calculator.index')
                <a href="{{ route('admin.calculator.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-calculator mr-2"></i>
                    Calculadora
                </a>
                <br>
            @endcan

            {{-- SUBTÍTULO CONCURSOS --}}
            @can('admin.tenderings.index')
                <span class="font-bold">Licitaciones</span>
                <hr>

                <a href="{{ route('admin.tenderings.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-star mr-2"></i>
                    Licitaciones de rollos
                </a>
                <br>
            @endcan

            {{-- SUBTÍTULO PARAMETRIZACIÓN --}}
            @can('admin.parameters.index')
                <span class="font-bold">Parametrización
                    <hr>
                </span>

                <a href="{{ route('admin.parameters.index') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-cogs mr-2"></i>
                    Parámetros
                </a>
                <br>
            @endcan

            {{-- SUBTÍTULO AUDITORÍA --}}
            @can('admin.auditory.index')
                <span class="font-bold">Auditoría
                    <hr>
                </span>

                <a href="{{ route('admin.auditory.clients') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Clientes
                </a>
                <a href="{{ route('admin.auditory.purchases') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Compras
                </a>
                <a href="{{ route('admin.auditory.tenderings') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Licitaciones
                </a>
                <a href="{{ route('admin.auditory.lots') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Lotes
                </a>
                <a href="{{ route('admin.auditory.purchase-orders') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Ordenes de compra
                </a>
                <a href="{{ route('admin.auditory.sale-orders') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Ordenes de venta
                </a>
                <a href="{{ route('admin.auditory.products') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Productos
                </a>
                <a href="{{ route('admin.auditory.previous-products') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Productos anteriores
                </a>
                <a href="{{ route('admin.auditory.following-products') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Productos siguientes
                </a>
                <a href="{{ route('admin.auditory.suppliers') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Proveedores
                </a>
                <a href="{{ route('admin.auditory.sublots') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Sublotes
                </a>
                <a href="{{ route('admin.auditory.tasks') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Tareas
                </a>
                <a href="{{ route('admin.auditory.users') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Usuarios
                </a>
                <a href="{{ route('admin.auditory.sales') }}"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Ventas
                </a>
                <br>
            @endcan

            {{-- MI PERFIL --}}
            <span class="font-bold">Cerrar sesión
                <hr>
            </span>

            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf

                <a href="{{ route('logout') }}" @click.prevent="$root.submit();"
                    class="block px-4 py-1 mt-2 text-sm font-semibold text-gray-900 rounded-lg dark-mode:bg-gray-700 dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                    <i class="fas fa-arrow-alt-circle-left mr-2"></i>
                    Cerrar sesión
                </a>
                <br>
            </form>

        </nav>
    </div>
</div>
