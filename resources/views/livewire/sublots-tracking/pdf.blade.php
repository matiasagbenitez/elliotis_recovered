<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle sublote PDF</title>

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
                    <img src="{{ base_path('storage/app/public/img' . $company_stats['logo']) }}" alt="Logo"
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
            {{ $report_title }} {{ $sublotStats['code'] }}
        </h1>
    </div>

    @if ($sublotStats)
        <h2 class="subtitle">
            Información del sublote
            <hr>
        </h2>
        <table style="width: 100%">
            <tr>
                <td style="width: 30%;" valign="top">
                    <img src="{{ base_path('storage/app/public/img/example_qrcode.png') }}" alt="Logo"
                        style="width: 130; height: 130; margin-right: 15px;">
                </td>

                <td style="width: 70%; margin-right: 15px" valign="top">

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        ID sublote:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['id'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Código sublote:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['code'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Lote:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['lot'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Tipo de producto:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['product'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Ubicación actual:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['area'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Tarea generadora:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['task'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Inicio tarea:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['started_at'] }} ({{ $sublotStats['started_by'] }})
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Fin tarea:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['finished_at'] }} ({{ $sublotStats['finished_by'] }})
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Stock original:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['initial_quantity'] }} {{ $sublotStats['initial_m2'] }}
                        </span>
                    </p>

                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Stock actual:
                        <span style="font-weight: 400;">
                            {{ $sublotStats['actual_quantity'] }} {{ $sublotStats['actual_m2'] }}
                        </span>
                    </p>

                </td>
            </tr>
        </table>
    @endif

    @if ($historic)
        <h2 class="subtitle">
            Histórico de tareas
            <hr>
        </h2>

        <div >
            @foreach (array_reverse($historic) as $task)
                <div class="break-inside-avoid">
                    <h2 style="font-weight: 700; font-size: 1rem; margin: 0px;">
                        <span>{{ $task['started_at'] }}: {{ $task['name'] }} (#{{ $task['task_id'] }})</span>
                    </h2>
                    <div>
                        <p style="font-size: 0.8rem; margin: 0px;">Inicio tarea: <span class="font-normal">{{ $task['started_at'] }}
                                ({{ $task['started_by'] }})</span></p>
                        <p style="font-size: 0.8rem; margin: 0px;">Fin tarea: <span class="font-normal">{{ $task['finished_at'] }}
                                ({{ $task['finished_by'] }})</span></p>
                    </div>
                    @foreach ($task as $key => $subtask)
                        @if (is_array($subtask) && $key !== 'sublots')
                            <h3 style="font-weight: 700; font-size: 0.8rem; margin: 5px 0px 0px 0px;">Tarea anterior: {{ $subtask['name'] }} (#{{ $subtask['task_id'] }})</h3>
                            <span style="font-size: 0.8rem; margin: 0px;">Sublotes de entrada</span>
                            <ul class="ml-20 list-disc">
                                @foreach ($subtask['sublots'] as $sublot)
                                    <li>{{ $sublot['code'] }} - {{ $sublot['product'] }}
                                        @if ($sublot['purchase'])
                                          (Compra {{ $sublot['m2'] }})
                                        @else
                                            {{ $sublot['m2'] }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif

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
