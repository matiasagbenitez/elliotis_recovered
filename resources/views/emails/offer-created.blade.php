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
        <h1 class="text-2xl uppercase text-center font-bold mb-4">¡Oferta creada con éxito!</h1>

        <p class="text-justify">
            Hola,
            <span class="font-bold">{{ $supplier->business_name }}! </span>
            Gracias por participar de la licitación. Te mostramos el detalle de tu última oferta:
        </p>

        <br>

        <ul class="list-disc ml-10">
            @foreach ($offer->products as $product)
                <li>
                    <p>
                        x{{ $product->pivot->quantity }} unidades de
                        <span class="font-bold">{{ $product->name }}</span>
                    </p>
                </li>
            @endforeach
        </ul>

        <br>

        <div class="text-">
            <p class="font-bold">Peso aproximado (TN) =
                <span class="font-normal">
                    {{ number_format($offer->tn_total, 2, ',', '.') }} TN
                </span>
            </p>
            <p class="font-bold">Precio TN (IVA incluido) =
                <span class="font-normal">
                    ${{ number_format($offer->total / $offer->tn_total, 2, ',', '.') }}
                </span>
            </p>
            <p class="font-bold">Total final =
                <span class="font-normal">
                    ${{ number_format($offer->total, 2, ',', '.') }}
                </span>
            </p>
        </div>

        <br>
        <hr>
        <br>

        <p class="text-justify">
            Recuerda que puedes <span class="font-bold">anular o modificar</span> tu oferta hasta el {{ Date::parse($tendering_end_date)->format('d-m-Y') }} a las {{ Date::parse($tendering_end_date)->format('H:i') }} horas.
            Para ello, haz click en el siguiente botón:
        </p>

        <br>

        <div class="flex justify-center items-center">
            <a href="{{ route('offer.edit', ['hash' => $offer->hash]) }}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                Gestionar mi oferta
            </a>
        </div>

        <br>

        <p class="text-justify">
            Ante cualquier duda, no dude en contactarse con nosotros.
        </p>

        <div class="flex flex-col font-bold text-right">
            <span>¡Muchas gracias!</span>
            <span>CHP e hijos</span>
        </div>


        {{-- <p>Para ver el concurso, haz click <a href="#">aquí</a>.</p> --}}

    </div>
</body>

</html>
