<div class="container py-6">

    <x-slot name="header">
            <h1 class="font-semibold text-center text-xl text-gray-800 leading-tight">Parámetros del sistema</h1>

    </x-slot>

    <div class="px-12 py-8 bg-white rounded-lg shadow columns-1">

        {{-- Empresa --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-chart-area mr-2"></i>
                <h1 class="font-bold uppercase">Configuración general del sistema</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.company.edit') }}"><p class="font-normal hover:underline">Información de la empresa</p></a>
            <a href="{{ route('admin.users.index') }}"><p class="font-normal hover:underline">Listado de usuarios del sistema</p></a>
            <a href="{{ route('admin.roles.index') }}"><p class="font-normal hover:underline">Roles y permisos del sistema</p></a>
        </div>

        {{-- GEOGRAFÍA --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-globe mr-2"></i>
                <h1 class="font-bold uppercase">Geografía</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.countries.index') }}"><p class="font-normal hover:underline">Países</p></a>
            <a href="{{ route('admin.provinces.index') }}"><p class="font-normal hover:underline">Provincias</p></a>
            <a href="{{ route('admin.localities.index') }}"><p class="font-normal hover:underline">Localidades</p></a>
        </div>


        {{-- COMPRAS, VENTAS E IVA --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-calculator mr-2"></i>
                <h1 class="font-bold uppercase">Compras, ventas e IVA</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.pucharse-parameters.index') }}"><p class="font-normal hover:underline">Parámetros de compras y ventas</p></a>
            <a href="{{ route('admin.iva-conditions.index') }}"><p class="font-normal hover:underline">Condiciones ante IVA</p></a>
            <a href="{{ route('admin.iva-types.index') }}"><p class="font-normal hover:underline">Tipos de IVA</p></a>
        </div>


        {{-- PRODUCTOS --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-columns mr-2"></i>
                <h1 class="font-bold uppercase">Productos</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.product-names.index') }}"><p class="font-normal hover:underline">Nombres y denominaciones</p></a>
            <a href="{{ route('admin.measures.index') }}"><p class="font-normal hover:underline">Medidas</p></a>
            <a href="{{ route('admin.unities.index') }}"><p class="font-normal hover:underline">Unidades</p></a>
            <a href="{{ route('admin.phases.index') }}"><p class="font-normal hover:underline">Etapas de productos en producción</p></a>
            <a href="{{ route('admin.product-types.index') }}"><p class="font-normal hover:underline">Tipos de productos</p></a>
            <a href="{{ route('admin.wood-types.index') }}"><p class="font-normal hover:underline">Tipos de madera</p></a>
        </div>

        {{-- PRODUCTOS --}}
        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-chart-area mr-2"></i>
                <h1 class="font-bold uppercase">Producción</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.types-of-tasks.index') }}"><p class="font-normal hover:underline">Tipos de tareas</p></a>
            <a href="{{ route('admin.task-statuses.index') }}"><p class="font-normal hover:underline">Estados de tareas</p></a>
            <a href="{{ route('admin.areas.index') }}"><p class="font-normal hover:underline">Áreas de la empresa</p></a>
            <a href="{{ route('admin.following-products.index') }}"><p class="font-normal hover:underline">Productos de salida</p></a>
            <a href="{{ route('admin.previous-products.index') }}"><p class="font-normal hover:underline">Productos anteriores</p></a>

        </div>

        <div class="mb-5">
            <div class="flex items-center">
                <i class="fas fa-cloud-rain mr-2"></i>
                <h1 class="font-bold uppercase">Previsión meteorológica</h1>
            </div>
            <hr class="my-2">

            <a href="{{ route('admin.api.index') }}"><p class="font-normal hover:underline">Configurar API del clima</p></a>
            <a href="{{ route('admin.api.edit-coordinates') }}"><p class="font-normal hover:underline">Configurar coordenadas de localización</p></a>

        </div>

    </div>

</div>
