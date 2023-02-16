<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle venta PDF</title>

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
                    <img src="{{ public_path($company_stats['logo']) }}" alt="Logo"
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
        <p style="font-size: 0.8rem; font-weight: 700;">{{ $report_subtitle }}</p>
    </div>

    <div>
        <h2 class="subtitle">
            Resumen
            <hr>
        </h2>
        <table style="width: 100%;">
            <tr>
                <td style="width: 1/4;">
                    <p style="font-weight: 700;font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Total
                        tareas
                        de corte</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $total_tareas_corte }} tareas
                    </span>
                </td>
                <td style="width: 1/4;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Total de
                        sublotes</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $cantidad_sublotes_cortados }} sublotes
                    </span>
                </td>
                <td style="width: 1/4;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Total
                        rollos
                        cortados</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $total_rollos }} rollos
                    </span>
                </td>
                <td style="width: 1/4" valign="top">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Tiempo
                        de
                        corte total</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $tiempo_corte_formateado }}
                    </span>
                </td>
            </tr>
            <br>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="width: 2/3">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Detalle de
                        rollos</p>
                    <ul>
                        @foreach ($productos_cortados as $item)
                            <li>
                                {{ $item['nombre'] }}: {{ $item['cantidad_consumida'] }} unidades
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>
        <br>
        <table style="width: 100%;">
            <tr>
                <td style="width: 1/5;">
                    <p style="font-weight: 700;font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Total de fajas</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $total_fajas_cortados }} unidades
                    </span>
                </td>
                <td style="width: 1/5;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Total m²</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $total_m2_cortados }} m²
                    </span>
                </td>
                <td style="width: 1/5;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Sublotes generados</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $cantidad_sublotes_fajas_cortadas }} sublotes
                    </span>
                </td>
                <td style="width: 1/5" valign="top">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">m² x hora</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $m2_x_hora }} m²/h
                    </span>
                </td>
                <td style="width: 1/5" valign="top">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Rollos x hora</p>
                    <span style="font-weight: 400; font-size: 0.8rem;">
                        {{ $rollos_x_hora }} rollos/h
                    </span>
                </td>
            </tr>
            <br>
        </table>
        <table style="width: 100%;">
            <tr>
                <td>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Detalle de
                        fajas</p>
                    <ul>
                        @foreach ($productos_fajas_cortadas as $item)
                            <li>
                                {{ $item['nombre'] }}: {{ $item['m2_producidos'] }} m²
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            <br>
            <tr>
                <td>
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px; text-transform: uppercase;">Top 5 días con mayor producción</p>
                    <ul>
                        @if ($top_5_dias)
                        @foreach ($top_5_dias as $item)
                            <li>
                                {{ $item['fecha'] }}: {{ $item['initial_m2'] }} m²
                            </li>
                        @endforeach
                    @else
                        <li>
                            No hay datos para mostrar
                        </li>
                    @endif
                    </ul>
                </td>
            </tr>
        </table>
    </div>


    </tr>

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
