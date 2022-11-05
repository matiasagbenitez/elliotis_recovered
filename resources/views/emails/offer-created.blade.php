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
            Gracias por participar del concurso.
        </p>

        <br>

        <p class="text-justify text-sm mb-2">
            Te mostramos el detalle de tu última oferta:
        </p>

        <x-responsive-table>
            <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="text-xs text-center text-gray-500 uppercase border-b border-gray-300 bg-gray-200">
                    <tr class="text-center">
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Producto
                        </th>
                        <th scope="col" class="w-1/4 px-4 py-2">
                            Cantidad
                        </th>
                        <th scope="col" class="w-1/4 py-2 px-4">
                            Precio unitario
                        </th>
                        <th scope="col" class="w-1/4 py-2 px-4">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($offer->products as $product)
                        <tr class="bg-gray-50 text-center">
                            <td class="px-6 py-2">
                                <p class="text-xs uppercase">
                                    {{ $product->name }}
                                </p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-xs uppercase">
                                    {{ $product->pivot->quantity }}
                                </p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-xs uppercase">
                                    ${{ number_format($product->pivot->price, 2, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-xs uppercase">
                                    ${{ number_format($product->pivot->subtotal, 2, ',', '.') }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-responsive-table>

        <br>

        <div class="text-xs">
            <p class="font-bold">Subtotal =
                <span class="font-normal">
                    ${{ number_format($offer->subtotal, 2, ',', '.') }}
                </span>
            </p>
            <p class="font-bold">IVA =
                <span class="font-normal">
                    ${{ number_format($offer->iva, 2, ',', '.') }}
                </span>
            </p>
            <p class="font-bold">Total =
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
            <a href="" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                Gestionar mi oferta
            </a>
        </div>

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
