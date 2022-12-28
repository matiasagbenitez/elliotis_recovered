<div class="container py-6">

    <x-slot name="header" class="pl-0">
        <div class="flex items-center justify-center">
            <h1 class="font-bold text-xl text-gray-800 leading-tight">Parámetros del sistema</h1>
        </div>
    </x-slot>

    <div class="px-6 py-4 bg-white rounded-lg shadow">

        {{-- GEOGRAFÍA --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-globe mr-2"></i>
                <h1 class="font-bold uppercase">Geografía</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.countries.index') }}"><p class="font-normal hover:font-bold">Países</p></a>
            <a href="{{ route('admin.provinces.index') }}"><p class="font-normal hover:font-bold">Provincias</p></a>
            <a href="{{ route('admin.localities.index') }}"><p class="font-normal hover:font-bold">Localidades</p></a>
        </div>


        {{-- COMPRAS, VENTAS E IVA --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-calculator mr-2"></i>
                <h1 class="font-bold uppercase">Compras, ventas e IVA</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.pucharse-parameters.index') }}"><p class="font-normal hover:font-bold">Parámetros de compras y ventas</p></a>
            <a href="{{ route('admin.iva-conditions.index') }}"><p class="font-normal hover:font-bold">Condiciones ante IVA</p></a>
            <a href="{{ route('admin.iva-types.index') }}"><p class="font-normal hover:font-bold">Tipos de IVA</p></a>
        </div>


        {{-- PRODUCTOS --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-columns mr-2"></i>
                <h1 class="font-bold uppercase">Productos</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.product-names.index') }}"><p class="font-normal hover:font-bold">Nombres y denominaciones</p></a>
            <a href="{{ route('admin.measures.index') }}"><p class="font-normal hover:font-bold">Medidas</p></a>
            <a href="{{ route('admin.unities.index') }}"><p class="font-normal hover:font-bold">Unidades</p></a>
            <a href="{{ route('admin.phases.index') }}"><p class="font-normal hover:font-bold">Etapas de productos en producción</p></a>
            <a href="{{ route('admin.product-types.index') }}"><p class="font-normal hover:font-bold">Tipos de productos</p></a>
            <a href="{{ route('admin.wood-types.index') }}"><p class="font-normal hover:font-bold">Tipos de madera</p></a>
        </div>

        {{-- PRODUCTOS --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-chart-area mr-2"></i>
                <h1 class="font-bold uppercase">Producción</h1>
            </div>
            <hr class="my-2">

            <a href="#"><p class="font-normal hover:font-bold">Tipos de tareas</p></a>
            <a href="{{ route('admin.task-statuses.index') }}"><p class="font-normal hover:font-bold">Estados de tareas</p></a>
            <a href="{{ route('admin.areas.index') }}"><p class="font-normal hover:font-bold">Áreas de la empresa</p></a>
            <a href="#"><p class="font-normal hover:font-bold">Tipos de movimientos</p></a>
        </div>

    </div>

</div>
