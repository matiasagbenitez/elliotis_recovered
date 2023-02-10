<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle compra PDF</title>

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
                    <img src="{{ public_path('/img/logo_empresa.png') }}" alt="Logo"
                        style="width: 130; height: 65; margin-right: 15px;">
                </td>
                <td style="width: 30%; margin-right: 15px" valign="top">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Empresa:
                        <span style="font-weight: 400;">
                            {{ $company_stats['name'] }}
                        </span>
                    </p>
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

    @if (!$purchase->is_active)
    <div style="margin: 10px 0px; border: 1px solid #EC4747; font-size: 0.8rem; padding: 5px; text-align: justify; color: #EC4747;">
        ¡Atención! La presente compra no es válida ya que anulada por el usuario {{ $user_who_cancelled }}
        el día {{ Date::parse($purchase->cancelled_at)->format('d/m/Y H:i') }}
        por el siguiente motivo: {{ $purchase->cancel_reason }}
    </div>
    @endif

    <div>
        <h2 class="subtitle">
            Datos de la orden
            <hr>
        </h2>
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            Razón social del proveedor:
            <span style="font-weight: 400;">
                {{ $data['supplier'] }}
            </span>
        </p>
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            CUIT:
            <span style="font-weight: 400;">
                {{ $data['cuit'] }}
            </span>
        </p>
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            Condición ante IVA:
            <span style="font-weight: 400;">
                {{ $data['iva_condition'] }} ({{ $data['discriminate'] }})
            </span>
        </p>
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            Fecha compra:
            <span style="font-weight: 400;">
                {{ $data['date'] }}
            </span>
        </p>
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            Peso total
            <span style="font-weight: 400;">
                {{ $data['total_weight'] }}
            </span>
        </p>
        <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
            Tipo de compra:
            <span style="font-weight: 400;">
                {{ $data['type_of_purchase'] }}
            </span>
        </p>
    </div>

    <div>
        <h2 class="subtitle">
            Datos del pago
            <hr>
        </h2>
        <table>
            <tr>
                <td style="width: 50%;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Método de pago:
                        <span style="font-weight: 400;">
                            {{ $data['payment_method'] }}
                        </span>
                    </p>
                </td>
                <td style="width: 50%; padding-left: 50px;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Condición de pago:
                        <span style="font-weight: 400;">
                            {{ $data['payment_condition'] }}
                        </span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Tipo de comprobante:
                        <span style="font-weight: 400;">
                            {{ $data['voucher_type'] }}
                        </span>
                    </p>
                </td>
                <td style="width: 50%; padding-left: 50px;">
                    <p style="font-weight: 700; font-size: 0.8rem; margin: 0px;">
                        Número de comprobante
                        <span style="font-weight: 400;">
                            {{ $data['voucher_number'] }}
                        </span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 15px;">
        <h2 class="subtitle">
            Detalle de la orden
            <hr>
        </h2>
        <table class="content-table">
            <thead class="table-head">
                <tr class="table-head-row">
                    <td style="width: 30%">
                        <p>{{ $titles['product'] }}</p>
                    </td>
                    <td style="width: 10%">
                        <p>{{ $titles['quantity'] }}</p>
                    </td>
                    <td style="width: 20%">
                        <p>{{ $titles['tn_total'] }}</p>
                    </td>
                    <td style="width: 20%">
                        <p>{{ $titles['tn_price'] }}</p>
                    </td>
                    <td style="width: 20%">
                        <p>{{ $titles['subtotal'] }}</p>
                    </td>
                </tr>
            </thead>
            <tbody>
                @if ($stats)
                    @foreach ($stats as $product)
                        <tr class="table-body-row">
                            <td style="text-align: left;">
                                <p>{{ $product['name'] }}</p>
                            </td>
                            <td>
                                <p>{{ $product['quantity'] }}</p>
                            </td>
                            <td>
                                <p>{{ $product['tn_total'] }}</p>
                            </td>
                            <td>
                                <p>{{ $product['tn_price'] }}</p>
                            </td>
                            <td>
                                <p>{{ $product['subtotal'] }}</p>
                            </td>
                        </tr>
                    @endforeach

                    @if ($supplier_discriminates_iva)
                        <tr style="font-weight: 700;">
                            <td colspan="4" style="text-align: right; font-size: 0.8rem; ">
                                Subtotal:
                            </td>
                            <td style="text-align: center; font-size: 0.8rem;">
                                <p style="margin: 5px 0px;">{{ $totals['subtotal'] }}</p>
                            </td>
                        </tr>
                        <tr style="font-weight: 700;">
                            <td colspan="4" style="text-align: right; font-size: 0.8rem; ">
                                IVA:
                            </td>
                            <td style="text-align: center; font-size: 0.8rem;">
                                <p style="margin: 5px 0px;">{{ $totals['iva'] }}</p>
                            </td>
                        </tr>
                        <tr style="font-weight: 700;">
                            <td colspan="4" style="text-align: right; font-size: 0.8rem; ">
                                Total:
                            </td>
                            <td style="text-align: center; font-size: 0.8rem;">
                                <p style="margin: 5px 0px;">{{ $totals['total'] }}</p>
                            </td>
                        </tr>
                    @else
                        <tr style="font-weight: 700;">
                            <td colspan="4" style="text-align: right; font-size: 0.8rem; ">
                                Total:
                            </td>
                            <td style="text-align: center; font-size: 0.8rem;">
                                <p style="margin: 5px 0px;">{{ $totals['total'] }}</p>
                            </td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="6" style="text-align: center; font-size: 0.8rem;">
                            <p>No hay datos para mostrar</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div>
            <p style="font-weight: 700; font-size: 0.8rem;">
                Observaciones:
                <span style="font-weight: 400;">
                    {{ $data['observations'] }}
                </span>
            </p>
        </div>

        <div style="margin-top: 30px; border: 1px solid black; font-size: 0.7rem; padding: 5px; text-align: center;">
            DETALLE DE COMPRA PARA USO INTERNO. DOCUMENTO NO VÁLIDO COMO FACTURA.
        </div>
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
