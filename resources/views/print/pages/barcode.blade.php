@extends('print.layouts.barcode-app')
@section('content')
    @foreach (range(1, $item['print_quantity']) as $count)
    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="data-block">
        <tbody>
            <tr>
                <td width="100%" align="left">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="100%" align="left" class="td-style">
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item['code'], 'C128') }}"
                                        class="barcode_image" height="120" width="100%" /><br />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="50%" align="left" class="td-barcode_data">
                                    <h3 class="fw-bold barcode-text" style="font-size: 1rem">{{ Carbon\Carbon::now()->format('d/m/Y') }}</h3>
                                </td>
                                <td width="50%" align="right" class="td-barcode_data">
                                    <h3 class="fw-bold barcode-text" style="font-size: 1rem"> {{ $item['code'] }} </h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="100%" align="center" class="td-barcode_data" >
                                    <h3 class="fw-bold barcode-text">
                                        {{ Illuminate\Support\Str::limit($item['name'], 33) }}
                                    </h3>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" align="center" class="td-barcode_data_price">
                                    <h3 class="fw-bold barcode-text" style="font-size: 3rem">LKR
                                        {{ number_format($item['selling'], 2) }}
                                    </h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach

    <style>
        .td-style {
            padding: 0 important;
            /* font-family: 'Consolas', monospace; */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .barcode_image {
            min-width: 100%;

        }

        .td-barcode_data {
            font-size: 1.5rem;
            padding: 0px !important;
            margin: 0px !important;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
        }

        .td-barcode_data_price {
            font-size: 2rem;
            padding: 0px !important;
            margin: 0px !important;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
        }

        .barcode-text{
            font-size: 1.9rem;
            padding: 0px !important;
            margin: 0px !important;
        }


        .data-block {
            margin-top: 0px;
            margin-left: 0px;
        }

        .td-margin-bottom {
            margin: 0px !important;
        }

        .padding-0 {
            width: 100%;
            margin-top: 0px !important;
            padding-top: 0px !important;
        }
    </style>
@endsection
