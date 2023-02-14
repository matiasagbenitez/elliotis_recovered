<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de producción PDF</title>

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
    </div>

    <h2 style="font-size: 1rem;">Resumen de producción</h2>
    <hr>

    <div style="font-size: 0.8rem;">
        @if ($stadistics['mt_count'] > 0)
            <p style="margin: 0px; font-weight: 700; text-decoration: underline;">Tareas de movimiento</p>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Cantidad de tareas</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['mt_count'] }} tareas</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Tiempo total de trabajo</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['total_time_mt'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Volumen trabajado</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['total_m2_mt'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Volumen trabajado por hora</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['m2_per_hour_mt'] }}</p>
                    </td>
                </tr>
            </table>
            @if ($stadistics['max_m2_mt_id'])
            <p>
                La tarea más productiva fue la #{{ $stadistics['max_m2_mt_id'] }},
                realizada el día {{ $stadistics['max_m2_mt_date'] }}
                y cuyo volumen de trabajo fue de {{ $stadistics['max_m2_mt'] }}.
            </p>
            @endif
            <br>
        @endif

        @if ($stadistics['tt_count'] > 0)
            <p style="margin: 0px; font-weight: 700; text-decoration: underline;">Tareas de transformación</p>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Cantidad de tareas</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['tt_count'] }} tareas</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Tiempo total de trabajo</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['total_time_tt'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Volumen trabajado</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['total_m2_tt'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Volumen trabajado por hora</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['m2_per_hour_tt'] }}</p>
                    </td>
                </tr>
            </table>
            @if ($stadistics['max_m2_tt_id'] != null)
            <p>
                La tarea más productiva fue la #{{ $stadistics['max_m2_tt_id'] }},
                realizada el día {{ $stadistics['max_m2_tt_date'] }}
                y cuyo volumen de trabajo fue de {{ $stadistics['max_m2_tt'] }}.
            </p>
            @endif
            <br>
        @endif

        @if ($stadistics['mtt_count'] > 0)
            <p style="margin: 0px; font-weight: 700; text-decoration: underline;">Tareas de movimiento y transformación
            </p>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Cantidad de tareas</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['mtt_count'] }} tareas</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Tiempo total de trabajo</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['total_time_mtt'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Volumen trabajado</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['total_m2_mtt'] }}</p>
                    </td>
                    <td style="width: 25%;">
                        <p style="margin: 0px; font-weight: 700;">Volumen trabajado por hora</p>
                        <p style="margin: 0px; font-weight: 400;">{{ $stadistics['m2_per_hour_mtt'] }}</p>
                    </td>
                </tr>
            </table>
            @if ($stadistics['max_m2_mtt_id'])
                <p>
                    La tarea más productiva fue la #{{ $stadistics['max_m2_mtt_id'] }},
                    realizada el día {{ $stadistics['max_m2_mtt_date'] }}
                    y cuyo volumen de trabajo fue de {{ $stadistics['max_m2_mtt'] }}.
                </p>
            @endif
            <br>
        @endif
    </div>

    <h2 style="font-size: 1rem;">Detalle de producción</h2>
    <hr>
    <div>
        <table class="content-table">
            <thead class="table-head">
                <tr class="table-head-row">
                    <td style="width: 5%">
                        <p>ID</p>
                    </td>
                    <td style="width: 25%">
                        <p>Tarea</p>
                    </td>
                    <td style="width: 15%">
                        <p>Fecha inicio</p>
                    </td>
                    <td style="width: 15%">
                        <p>Usuario inicio</p>
                    </td>
                    <td style="width: 15%">
                        <p>Fecha fin</p>
                    </td>
                    <td style="width: 15%">
                        <p>Usuario fin</p>
                    </td>
                    <td style="width: 10%">
                        <p>Volumen</p>
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats as $stat)
                    <tr class="table-body-row" style="text-transform: uppercase; font-size: 0.7rem;">
                        <td>
                            <p>{{ $stat['task_id'] }}</p>
                        </td>
                        <td style="text-align:left;">
                            <p>{{ $stat['type_of_task'] }}</p>
                        </td>
                        <td>
                            <p>{{ $stat['started_at'] }}</p>
                        </td>
                        <td>
                            <p>{{ $stat['started_by'] }}</p>
                        </td>
                        <td>
                            <p>{{ $stat['finished_at'] }}</p>
                        </td>
                        <td>
                            <p>{{ $stat['finished_by'] }}</p>
                        </td>
                        <td style="text-align:right;">
                            <p>{{ $stat['volume'] }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="observaciones" style="border: .5px solid black; margin-top: 15px;">
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            Filtros:
        </p>
        <ul style="list-style-type: disc; margin: 5px 0px;">
            @foreach ($filtros as $key => $value)
                <li>{{ $key }}: {{ $value }}</li>
            @endforeach
        </ul>
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
