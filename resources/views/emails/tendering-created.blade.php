<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tendering Created</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
</head>

<body class="container w-2/3 my-6 bg-gray-100">
    <div class="bg-white shadow p-6 rounded-xl">
        <h1 class="text-2xl uppercase text-center font-bold mb-4">Concurso privado de precios</h1>

        <p class="text-justify">
            Hola,
            <span class="font-bold">{{ $supplier->business_name }}!</span>
            Se ha lanzado un nuevo concurso privado de precios para la empresa CHP e hijos y estás anotado como
            participante. Puedes
            <span class="font-bold">ver el concurso y completar tu oferta </span>
            haciendo click en el siguiente botón:
        </p>

        <br>

        <div class="flex justify-center items-center">
            <a href="{{ route('offer.create', $hash) }}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                Ver concurso
            </a>
        </div>

        <br>

        <p class="text-justify text-sm">
            Si no puedes acceder al concurso, copia y pega el siguiente link en tu navegador:
            <a href="{{ route('offer.create', $hash) }}" class="text-blue-600 hover:font-bold">https://www.chpehijos.com.ar/offer/create/{{ $hash->hash }}</a>
        </p>

        <br>

        <p class="text-justify text-sm">
            Antes de enviar tu oferta, recuerda que debes tener en cuenta los siguientes puntos:
        <ul class="text-xs list-disc list-inside ml-4 italic">
            <li>El precio debe ser en pesos argentinos.</li>
            <li>El precio debe ser por unidad.</li>
            <li>El precio debe ser fijo y no puede variar.</li>
            <li>El precio final incluye IVA por facturación.</li>
            <li>El envío de la oferta supone un compromiso de venta para la fecha estipulada.</li>
            <li>Podrá modificar o anular la oferta antes de la fecha de cierre.</li>
        </ul>
        </p>

        <br>

        <p class="text-justify">
        <p>Ante cualquier duda, no dude en contactarse con nosotros.</p>
        </p>

        <br>

        <div class="flex flex-col font-bold text-right">
            <span>¡Muchas gracias!</span>
            <span>CHP e hijos</span>
        </div>


        {{-- <p>Para ver el concurso, haz click <a href="#">aquí</a>.</p> --}}

    </div>
</body>

</html>
