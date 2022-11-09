<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Offer Created</title>

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
        <h1 class="text-2xl uppercase text-center font-bold mb-4">¡Oferta anulada con éxito!</h1>

        <p class="text-justify">
            Hola,
            <span class="font-bold">{{ $supplier_business_name }}! </span>
            Te informamos que tu oferta fue anulada correctamente. Esperamos verte participar en otra oportunidad.
        </p>

        <br>

        <p class="text-justify">
            Ante cualquier duda, no dude en contactarse con nosotros.
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
