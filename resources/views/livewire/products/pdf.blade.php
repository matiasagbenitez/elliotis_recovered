<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Productos PDF</title>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        /* Margin top 0 */
        .report-title {
            margin: 15px 0px;
            font-size: 1.25rem;
            text-align: center;
            text-transform: uppercase;
        }

        .content-table {
            width: 100%;
        }

        .table-head {
            border-bottom: 1px solid #000;
            background-color: #e0e0e0;
        }

        .table-head-row {
            text-transform: uppercase;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .table-body-row {
            padding: 5px 0px;
            text-align: center;
            font-size: 0.8rem;
        }

        .table-body-row td>p {
            margin: 5px 0;
        }

    </style>
</head>

<body>

    <div>
        <table>
            <tr>
                <td style="width: 40%;">
                    {{-- <img src="{{ asset('/img/logo_empresa.png') }}" alt="Logo" style="width: 250px; height: 170px;"> --}}
                    <img src="{{ public_path('/img/logo_empresa.png') }}" alt="Logo" style="width: 160; height: 90;">
                </td>
                <td style="width: 100%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin-top: 0; margin-bottom: 5px;">
                        Empresa:
                        <span style="font-weight: 400;">
                            {{ $company_stats['name'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin-top: 0; margin-bottom: 5px;">
                        Dirección:
                        <span style="font-weight: 400;">
                            {{ $company_stats['address'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin-top: 0; margin-bottom: 5px;">
                        Código postal:
                        <span style="font-weight: 400;">
                            {{ $company_stats['cp'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin-top: 0; margin-bottom: 5px;">
                        Teléfono de contacto:
                        <span style="font-weight: 400;">
                            {{ $company_stats['phone'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin-top: 0; margin-bottom: 5px;">
                        Correo electrónico:
                        <span style="font-weight: 400;">
                            {{ $company_stats['email'] }}
                        </span>
                    </p>
                </td>
            </tr>
        </table>
        <hr>
    </div>

    <div>
        <h1 class="report-title">
            {{ $report_title }}
        </h1>
    </div>

    <div>
        <table class="content-table">
            <thead class="table-head">
                <tr class="table-head-row">
                    <td style="width: 5%">
                        <p>ID</p>
                    </td>
                    <td style="width: 40%">
                        <p>Producto</p>
                    </td>
                    <td style="width: 15%">
                        <p>Especie</p>
                    </td>
                    <td style="width: 15%">
                        <p>Estado</p>
                    </td>
                    <td style="width: 12.5%">
                        <p>Unidades</p>
                    </td>
                    <td style="width: 12.5%">
                        <p>M2</p>
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats as $stat)
                    <tr class="table-body-row">
                        <td style="width: 5%">
                            <p>{{ $stat['id'] }}</p>
                        </td>
                        <td style="width: 40%; text-align:left;">
                            <p>{{ $stat['name'] }}</p>
                        </td>
                        <td style="width: 15%">
                            <p>{{ $stat['wood_type'] }}</p>
                        </td>
                        <td style="width: 15%">
                            <p>{{ $stat['stock_level'] }}</p>
                        </td>
                        <td style="width: 12.5%">
                            <p>{{ $stat['stock'] }}</p>
                        </td>
                        <td style="width: 12.5%; text-align:right;">
                            <p>{{ $stat['m2'] }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</body>

</html>
