<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle tarea PDF</title>

    <style>
        @page {
            margin-bottom: 80px;
        }

        body {
            font-family: 'Nunito', sans-serif;
        }

        /* Margin top 0 */
        .report-title {
            margin: 10px 0px;
            font-size: 1.25rem;
            text-align: center;
            text-transform: uppercase;
        }

        .subtitle {
            margin: 10px 0px;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .content-table {
            width: 100%;
            table-layout: fixed;
        }

        .table-head {
            /* border-bottom: 1px solid #000; */
            background-color: #e0e0e0;
        }

        .table-head-row {
            text-transform: uppercase;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .table-head td {
            padding: 0px;
        }

        .table-body-row {
            padding: 10px 0px;
            text-align: center;
            font-size: 0.8rem;
        }

        .table-body-row td>p {
            margin: 5px 0;
        }

        .page-number {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .footer {
            position: fixed;
            bottom: 0;
            right: 0;
        }

        li {
            font-size: 0.8rem;
        }

        .observaciones {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            padding-right: 10px;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div>
        <table>
            <tr>
                <td style="width: 40%;" valign="top">
                    {{-- <img src="{{ asset('/img/logo_empresa.png') }}" alt="Logo" style="width: 250px; height: 170px;"> --}}
                    <img src="{{ base_path('storage/app/public/img' . $company_stats['logo'])  }}" alt="Logo"
                        style="width: 130; height: 65; margin-right: 15px;">
                </td>
                <td style="width: 30%; margin-right: 15px" valign="top">
                    <span style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Razón social:
                        <span style="font-weight: 400;">
                            {{ $company_stats['name'] }}
                        </span>
                    </span>
                    <span style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        CUIT:
                        <span style="font-weight: 400;">
                            {{ $company_stats['cuit'] }}
                        </span>
                    </span>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Dirección:
                        <span style="font-weight: 400;">
                            {{ $company_stats['address'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Código postal:
                        <span style="font-weight: 400;">
                            {{ $company_stats['cp'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Teléfono de contacto:
                        <span style="font-weight: 400;">
                            {{ $company_stats['phone'] }}
                        </span>
                    </p>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Correo electrónico:
                        <span style="font-weight: 400;">
                            {{ $company_stats['email'] }}
                        </span>
                    </p>
                </td>
                <td style="width: 100%;" valign="top">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; margin-left: 55px;">
                        {{ $company_stats['date'] }}
                    </p>
                    <p style="font-size: 0.8rem; margin: 0px; margin-left: 55px; text-align:right">
                        {{ $company_stats['user'] }}
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
        <h2 class="subtitle">
            Datos de la tarea
            <hr>
        </h2>
        <table style="width: 100%;">
            <tr>
                <td>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Tipo de
                        tarea</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['type_of_task_name'] }}
                    </span>
                </td>
            </tr>
            <br>
            <tr>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Iniciada por
                    </p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['started_by'] }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Fecha de
                        inicio</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['started_at'] }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Finalizada
                        por</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['finished_by'] }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Fecha de fin
                    </p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['finished_at'] }}
                    </span>
                </td>
            </tr>
            <br>
            <tr>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Área origen
                    </p>
                    <span style="font-weight: 400; font-size: 0.8rem;  padding: 2px;">
                        {{ $data['origin_area'] }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Etapa
                        inicial</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['initial_phase'] }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Área destino
                    </p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['destination_area'] }}
                    </span>
                </td>
                <td style="width: 25%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Etapa final
                    </p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $data['final_phase'] }}
                    </span>
                </td>
            </tr>

        </table>
    </div>

    <div style="margin-top: 15px;">
        <h2 class="subtitle">
            {{ $movement || $initial ? 'Detalle de movimiento' : 'Detalle de producción' }}
            <hr>
        </h2>

        <h3 style="font-size: 0.8rem;">Detalle inicial</h3>

        <table style="width: 100%;">
            <thead>
                <tr style="white-space: nowrap; background-color: #ECECEC; text-transform: uppercase;">
                    <th style="width: 12.5%; text-align: center; font-size: 0.8rem; padding: 5px;">
                        Lote
                    </th>
                    <th
                        style="{{ $initial ? 'width: 25%;' : 'width: 12.5%;' }} text-align: center; font-size: 0.8rem;">
                        {{ $initial ? 'Proveedor' : 'Sublote' }}
                    </th>
                    <th style="{{ $initial ? 'width: 25%;' : 'width: 50%;' }} text-align: center; font-size: 0.8rem;">
                        Producto
                    </th>
                    <th style="width: 10%; text-align: center; font-size: 0.8rem;">
                        {{ $movement || $initial || $movement_transformation ? 'Stock original' : 'Cantidad consumida' }}
                    </th>
                    <th style="width: 10%; text-align: center; font-size: 0.8rem; white-space: nowrap;">
                        M2
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results['input_data'] as $item)
                    <tr style="font-size: 0.8rem; text-align: center; text-transform: uppercase;">
                        <td style="text-align: center; padding: 2px;">
                            {{ $item['lot_code'] }}
                        </td>
                        <td>
                            {{ $item['sublot_code'] }}
                        </td>
                        <td>
                            {{ $item['product_name'] }}
                        </td>
                        <td style="text-align: center;">
                            {{ $item['quantity'] }}
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            {{ $item['m2'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <h3 style="font-size: 0.8rem;">Detalle final</h3>

        <table style="width: 100%;">
            <thead>
                <tr style="white-space: nowrap; background-color: #ECECEC; text-transform: uppercase;">
                    <th style="width: 12.5%; text-align: center; font-size: 0.8rem; padding: 5px;">
                        Lote
                    </th>
                    <th
                        style="width: 12.5%; text-align: center; font-size: 0.8rem;">
                        Sublote
                    </th>
                    <th style="width: 50%; text-align: center; font-size: 0.8rem;">
                        Producto
                    </th>
                    <th style="text-align: center; font-size: 0.8rem;">
                        {{ $movement || $initial || $movement_transformation ? 'Cantidad movida' : 'Cantidad producida' }}
                    </th>
                    <th style="text-align: center; font-size: 0.8rem;">
                        M2
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results['output_data'] as $item)
                    <tr style="font-size: 0.8rem; text-transform: uppercase; text-align: center">
                        <td style="text-align: center; ">
                            {{ $item['lot_code'] }}
                        </td>
                        <td>
                            {{ $item['sublot_code'] }}
                        </td>
                        <td>
                            {{ $item['product_name'] }}
                        </td>
                        <td style="text-align: center;">
                            {{ $item['quantity'] }}
                        </td>
                        <td style="text-align: center;  white-space: nowrap;">
                            {{ $item['m2'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <script type="text/php">
        if (isset($pdf)) {
            //Shows number center-bottom of A4 page with $x,$y values
            $x = 490;  //X-axis i.e. vertical position
            $y = 795; //Y-axis horizontal position
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";  //format of display message
            $font =  $fontMetrics->get_font("helvetica", "bold");
            $size = 9;
            $color = array(0.2, 0.094, 0.0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>

</body>

</html>
