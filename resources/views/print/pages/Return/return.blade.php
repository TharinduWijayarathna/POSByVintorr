@extends('print.layouts.app')
@section('content')
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <thead>
            <tr>
                <th width="25%">


                    @isset($config->bill_logo_url)
                        <img src="{{ $config->bill_logo_url }}" class="brand-logo" />
                    @else
                        <img src="logo/logo.webp" class="brand-logo" />
                    @endisset


                </th>
                <th width="75%" class="text-center">
                    @isset($config->name)
                        <div class="header-title text-center">{{ $config->name }}</div>
                    @endisset

                    @isset($config->address)
                        <div class="header-sub-title" style="font-size: 12px;">{{ $config->address }}</div>
                    @endisset

                    @isset($config->email)
                        <div class="header-sub-title" style="font-size: 12px;">{{ $config->email }}</div>
                    @endisset

                    @isset($config->mobile)
                        <div class="header-sub-title mb-3" style="font-size: 12px;">{{ $config->mobile }}</div>
                    @endisset

                </th>
            </tr>
        </thead>
    </table>

    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="top-margin">
        <thead>
            <tr class="heading-bg-po">
                <th colspan="2">
                    <div class="header-sub-title text-left" style="font-size: 13px">Return Notice</div>
                </th>
            </tr>
            <tr class="heading-bg-po">
                <th width="45%" style="vertical-align: top;">
                    <div class="sub-title text-left" style="font-size: 10px">Return No : #{{ $order->code }}</div>
                </th>
                <th width="53%" style="vertical-align: top; padding-right: 0px;" class="right-text invoice-item-text">
                    <div class="sub-title text-right invoice-item-text item-margin" style="font-size: 10px;">Customer :</div>
                </th>
            </tr>
            <tr class="heading-bg-po">
                <th width="50%" style="vertical-align: top;">
                    <div class="sub-title text-left" style="font-size: 10px">Cashier : {{ $order->cashier_name }}</div>
                </th>
                <th style="vertical-align: top; padding-right: 0px;" class="right-text invoice-item-text">
                    <div  width="50%" class="sub-title text-right invoice-item-text item-margin" style="font-size: 10px;">
                    @isset( $order->customer )
                            @php
                                $name = $order->customer_name;
                                $lines = [];
                                $maxLength = 15;

                                while (strlen($name) > $maxLength) {
                                    $lastSpace = strrpos(substr($name, 0, $maxLength), ' ');

                                    if ($lastSpace !== false) {
                                        $line = substr($name, 0, $lastSpace);
                                        $name = substr($name, $lastSpace + 1);
                                    } else {
                                        $line = substr($name, 0, $maxLength);
                                        $name = substr($name, $maxLength);
                                    }
                                    $lines[] = $line;
                                }
                                if (strlen($name) > 0) {
                                    $lines[] = $name;
                                }
                            @endphp
                            @foreach ($lines as $line)
                                {{ $line }}<br>
                            @endforeach
                        @else
                            Walking Customer
                        @endisset
                        </div>
                    </div>
                </th>
            </tr>
        </thead>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="item-section">
        <thead class="">
            <tr class="row-bg" style="height: 50px;">
                <td colspan="2" align="center" class="td-style" style="font-size: 13px">
                    <b>Code</b>
                    <br />
                    <b>Product Name</b>
                </td>
                <td width="17%" align="right" class="td-style " style="font-size: 13px; vertical-align: top;">
                    <b>Qty</b>
                </td>
                <td width="30%" align="right" class="td-style " style="font-size: 13px">
                    <b>Rate ({{ $order->currency_name }})</b>
                    <br />
                    <b>Total</b>
                </td>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($order_items as $key => $item)
                <tr class="row-bg">
                    <td colspan="2" align="left">
                        <div class="invoice-item-text item-margin" style="font-size: 13px; margin-top: 6px;">
                            <b>{{ $item->product_code }}</b>
                            @if ($item->fgData && $item->fgData->name)
                                <div class="dotted-line-hr"></div>
                                <p style="word-wrap: break-word; margin-top: -5px;"><b>{{ $item->fgData ? $item->fgData->name : '' }}</b></p>
                            @endif
                        </div>
                    </td>
                    <td width="17%" align="right" class="right-text-qty" style="padding-right: 0px;">
                        <div class="invoice-item-text-qty item-margin" style="font-size: 13px">
                            <b>{{ number_format(abs($item->quantity), 0, '.', ',') }}</b>
                        </div>
                    </td>
                    <td width="25%" align="left" class="right-text invoice-item-text" style="padding-right: 0px;">
                        <div class="invoice-item-text item-margin" style="font-size: 13px">
                            <b>{{ number_format($item->unit_price, 2) }}</b>
                            <br />
                            <b>{{ number_format($item->sub_total, 2) }}</b>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="item-section">
        <tbody class=" total-text top-margin">

            <tr>
                <td width="100%" colspan="2">
                    <div class="line-hr"></div>
                </td>
            </tr>
            <tr class="row-bg">
                <td width="60%" class="right-text bold-text" style="text-align: right; font-size: 13px;">
                    Return Amount
                </td>
                <td width="40%" class="right-text bold-text" style="text-align: right; padding-right: 0px; font-size: 13px;">

                    {{ number_format(abs($order->total), 2) }}

                </td>
            </tr>

        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="section-table">
        <tbody>
            <tr class="row-bg">
                <td align="center">
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order['code'], 'C39') }}" height="50"
                        width="180" /><br />
                </td>
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="section-footer">
        <thead>
            @isset($order->remark)
                <tr>
                    <td class="left-text bold-text" style="padding-bottom: 15px; font-size: 13px; white-space: pre-line;">
                        {{ $order->remark }}
                    </td>
                </tr>
            @endisset
            <tr>
                <td class="footer-content" style="font-size: 12px">
                    <b> Date {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }} </b>
                </td>
            </tr>
        </thead>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="">
        <thead>
            <tr>
                <td class="terms" style="font-size: 15px;">
                    <b>
                        <div>
                        @isset($config->footer)
                            @php
                                $footer = $config->footer;
                                $lines = [];
                                $maxLength = 35;

                                while (strlen($footer) > $maxLength) {
                                    $lastSpace = strrpos(substr($footer, 0, $maxLength), ' ');

                                    if ($lastSpace !== false) {
                                        $line = substr($footer, 0, $lastSpace);
                                        $footer = substr($footer, $lastSpace + 1);
                                    } else {
                                        $line = substr($footer, 0, $maxLength);
                                        $footer = substr($footer, $maxLength);
                                    }

                                    $lines[] = $line;
                                }

                                if (strlen($footer) > 0) {
                                    $lines[] = $footer;
                                }
                            @endphp

                            @foreach ($lines as $line)
                                {{ $line }}<br>
                            @endforeach
                        @else
                            *Thank you for your visit*
                        @endisset
                        </div>
                    </b>
                </td>
            </tr>
        </thead>
    </table>
    <style>
        .item-margin {
            margin-bottom: 5px;
        }

        .top-margin {
            margin-top: 10px;
        }

        .page_break {
            page-break-before: always;
        }

        .right-text {
            font-family: 'Consolas', monospace;
            text-align: right;
            padding-right: 5px;
            font-size: .7rem;
        }

        .right-text-qty {
            font-family: 'Consolas', monospace;
            text-align: right;
            padding-right: 4px;
            font-size: .5rem;
        }

        .bold-text {
            font-weight: bold;
        }

        .row-style {
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .row-bg {
            background-color: #ffffff;
        }

        .row-bg-subtotal {
            background-color: #e8e8e8c4;
        }

        .row-bg-head {
            background-color: #dcdcdcb1;
        }

        .row-white {
            background-color: #ffffff;
        }

        .td-style {
            font-family: 'Consolas', monospace;
            font-size: 12px;
            font-weight: 400;
            line-height: 17px;
            padding-left: 10px;
            padding-bottom: 3px;
            padding-top: 3px;
        }

        .td-style-head {
            font-family: 'Consolas', monospace;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            padding-left: 10px;
            padding-bottom: 1px;
            padding-top: 1px;
        }

        .td-style-gt {
            font-family: 'Consolas', monospace;
            font-size: 14px;
            font-weight: 400;
            line-height: 17px;
            padding-left: 10px;
            padding-bottom: 6px;
            padding-top: 6px;
        }

        .signature {
            text-align: center;
            line-height: 10px;
        }

        .signature-section {
            margin-top: 50px;
        }

        .material-img {
            height: 120px;
            position: fixed;
            right: 150px;
            top: 160px;
            z-index: 999999;
            padding: 5px 0px 5px 0px;
        }

        .border-mb {
            border-bottom: #000000 solid 1px;
        }

        .border-mt {
            border-top: #000000 solid 1px;
        }

        .border-b {
            border-bottom: #000000 solid 1px;
        }

        .border-t {
            border-top: #000000 solid 1px;
        }

        .border-l {
            border-left: #000000 solid 1px;
        }

        .border-r {
            border-right: #000000 solid 1px;
        }

        .brand-logo {
            width: 70px;
            padding-bottom: 5px;
            padding-top: 2px;
        }

        .invoice-head {
            font-family: 'Consolas', monospace !important;
            font-size: 1.5rem;
        }

        .company-title {
            font-family: 'Consolas', monospace !important;
            font-size: 1rem;
            font-weight: 400;
        }

        .sub-title {
            font-family: 'Consolas', monospace !important;
            font-size: 0.6rem;
        }

        .header-title {
            font-family: 'Liberation Mono' !important;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .header-sub-title {
            font-family: 'Consolas', monospace !important;
            font-size: 0.8rem;
        }

        .text-left {
            text-align: left;
        }

        .company-address {
            font-family: 'Consolas', monospace !important;
            font-size: 0.8rem;
        }

        .company-tel {
            font-family: 'Consolas', monospace !important;
            font-size: 0.6rem;
        }

        .company-mail {
            font-family: 'Consolas', monospace !important;
            font-size: 0.6rem;
        }

        .invoice-item-text {
            font-family: 'Consolas', monospace;
            font-size: 0.7rem;
            /* font-weight: 300; */
        }

        .invoice-item-text-qty {
            font-family: 'Consolas', monospace;
            font-size: 0.7rem;
            /* font-weight: 300; */
        }

        .total-text {
            font-family: 'Consolas', monospace !important;
            font-size: 0.6rem;
            /* font-weight: 300; */
        }


        .heading-bg {
            background-color: #e8e8e8c4;
        }

        .heading-bg-po {
            font-family: 'Consolas', monospace;
            background-color: #ffffff7d;
            color: #2b2b2b;
        }

        .total-bg {
            background-color: #e8e8e8c4;
            padding-right: 10px;
            font-family: 'Consolas', monospace;
            font-size: 10px;
            font-weight: 400;
            line-height: 20px;
            padding-left: 10px;
            padding-bottom: 5px;
        }

        .total-txt {
            text-align: left;
            padding-left: 10px;
            font-family: 'Consolas', monospace;
            font-size: 10px;
            font-weight: 400;
            line-height: 20px;
            font-weight: bold;
        }

        .total-value {
            text-align: right;
            font-family: 'Consolas', monospace;
            font-size: 10px;
            font-weight: 400;
            line-height: 20px;
            font-weight: bold;
        }

        .table-heading {
            font-family: 'Consolas', monospace !important;
            padding-left: 15px;
            font-size: 12px;
        }

        .footer-content {
            font-family: 'Consolas', monospace !important;
            text-align: center;
            font-size: 8px;
        }

        .section-table {
            margin-bottom: 20px;
        }

        .section-footer {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .section-table {
            margin-bottom: 20px;
        }

        .section-table {
            margin-top: 20px;
        }

        .note-area {
            border-bottom: #c8c8c8ab solid 1px;
            border-top: #c8c8c8ab solid 1px;
            border-left: #c8c8c8ab solid 1px;
            border-right: #c8c8c8ab solid 1px;
            border-radius: 5px;
            margin-top: 50px;
        }

        .text {
            text-align: left;
            margin-top: 20px;
            padding-bottom: 20px;
            margin-left: 20px;
        }

        .text-head {
            font-family: 'Consolas', monospace;
            font-size: 30px;
            font-weight: bold;
        }

        .text-body {
            font-family: 'Consolas', monospace;
            font-size: 15px;
        }

        .text-tc {
            font-family: 'Consolas', monospace !important;
            font-size: 12px;
            line-height: 20px;
        }

        .vendor-info {
            font-family: 'Consolas', monospace !important;
            font-size: 10px;
            line-height: 5px;
        }

        .item-section {
            margin-top: 5px;
        }

        .terms {
            font-family: 'Consolas', monospace;
            font-size: 0.6rem;
            text-align: center;
        }

        .invoice-order-item {
            border-bottom: #000000 solid 1px;
        }

        .line-hr {
            border-bottom: #000000 solid 1px;
            margin-top: 1px;
            margin-bottom: 1px;
        }

        .dotted-line-hr {
            border-bottom: #000000 dotted 1px;
        }
    </style>
@endsection
