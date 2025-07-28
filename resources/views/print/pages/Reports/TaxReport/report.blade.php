@extends('print.layouts.report')
@section('content')
    <div class="table-responsive">
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td style="padding-left: 0;">
                        <div class="text-head" style="margin-bottom: 10px; color: {{ $config->color_code }};"><b>TAX
                                REPORT</b></div>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="10%" class="td-customer-style customer-section-name"
                        style="padding-left: 0;">
                        <div>FROM<div>
                                <div style="margin-bottom: 3px;margin-top: 6px;">TO</div>
                                <div style="margin-bottom: 3px;margin-top: 6px;">CURRENCY</div>
                                @if ($order_type != null)
                                    <div style="margin-bottom: 3px;margin-top: 6px;">ORDER TYPE</div>
                                @endif
                                @if ($code != null)
                                    <div style="margin-bottom: 3px;margin-top: 6px;">CODE</div>
                                @endif

                    </td>
                    <td align="left" width="35%" class="td-customer-style "
                        style="padding-right: 10;vertical-align:text-top;">
                        <div>
                            {{ \Carbon\Carbon::parse($search_order_from_date)->setTimezone('Asia/Colombo')->format('d/m/Y') }}
                        </div>
                        <div style="margin-bottom: 3px;margin-top: 6px;">
                            {{ \Carbon\Carbon::parse($search_order_to_date)->setTimezone('Asia/Colombo')->format('d/m/Y') }}
                        </div>
                        <div style="margin-bottom: 3px;margin-top: 6px;">
                            {{ $currency['code'] }}
                        </div>
                        @if ($order_type != null)
                            <div style="margin-bottom: 3px;margin-top: 6px;">
                                {{ $order_type }}
                            </div>
                        @endif
                        @if ($code != null)
                            <div style="margin-bottom: 3px;margin-top: 6px;">
                                {{ $code }}
                            </div>
                        @endif

                    </td>

                </tr>
            </tbody>
        </table>
    </div>


    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="invoice_table">
        <thead class="invoice_table_head" style="">
            <tr class="row-bg-head"
                style="line-height:1; white-space:nowrap; color: {{ $config->color_code }}; background-color: {{ $config->color_code }}20;">
                <th width="10%" align="left" class="table_head_data" style="font-size: 11px;">
                    CODE
                </th>
                <th width="20%" align="left" class="table_head_data " style="font-size: 11px;">
                    CUSTOMER
                </th>
                <th width="10%" align="left" class="table_head_data" style="font-size: 11px;">
                    DATE
                </th>
                <th width="20%" align="right" class="table_head_data" style="font-size: 11px;">
                    TOTAL WITHOUT TAX
                </th>
                <th width="20%" align="right" class="table_head_data" style="font-size: 11px;">
                    TOTAL WITH TAX
                </th>
                <th width="20%" align="right" class="table_head_data" style="font-size: 11px;">
                    TOTAL TAX
                </th>

            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $key => $order)
                <tr class="row-bg">
                    <td width="10%" align="left" class="td-style">
                        <p style="margin: 0px">
                            {{ $order['code'] }}
                        </p>
                    </td>
                    <td width="20%" align="left" class="td-style">
                        <p style="margin: 0px">
                            @if ($order['customer_name'] == null)
                                Walking Customer
                            @else
                                {{ $order['customer_name'] }}
                            @endif
                        </p>
                    </td>
                    <td width="10%" align="left" class="td-style">
                        <p style="margin: 0px">
                            {{ \Carbon\Carbon::parse($order['date'])->setTimezone('Asia/Colombo')->format('d/m/Y') }}
                        </p>
                    </td>
                    <td width="20%" align="right" class="td-style right-text">
                        {{ number_format($order['sub_total'], 0, '.', ',') }}
                    </td>
                    <td width="20%" align="right" class="td-style right-text">
                        {{ number_format($order['total'], 0, '.', ',') }}
                    </td>
                    <td width="20%" align="right" class="td-style right-text">
                        {{ number_format($order['total_tax'], 2) }}
                    </td>
                </tr>
            @endforeach
            <tr class="row-bg " style="border-top: 2px dotted #eee;">
                <td width="10%" class="right-text" style="padding-bottom: 0px; padding-top: 5px;"></td>
                <td width="20%" class="right-text" style="padding-bottom: 0px; padding-top: 5px;"></td>
                <td width="10%" class="right-text" style="padding-bottom: 0px; padding-top: 5px;"></td>
                <td width="20%" class="right-text" style="padding-bottom: 0px; padding-top: 5px;"></td>
                <td width="20%" align="right" class="td-style left-text"
                    style="color: #808080;white-space:nowrap; padding-bottom: 0px; padding-top: 5px; font-weight: bold;">
                    TOTAL</td>
                <td width="20%" class="td-style right-text"
                    style="padding-bottom: 0px;white-space:nowrap; padding-top: 5px; font-weight: bold;">
                    {{ number_format($total_tax_sum, 2) }}
                </td>
            </tr>


        </tbody>
    </table>

    <style>
        .page_break {
            page-break-before: always;
        }

        .ql-cursor {
            display: none;
        }

        .right-text {
            text-align: right;
            padding-right: 5px;
        }

        .row-style {
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .row-bg-head {
            background-color: #f5f5f5;
        }

        .row-bg {
            background-color: transparent;
        }

        .invoice_table th,
        td {
            padding: 10px;
        }

        .parameter-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .parameter-td-style-head {
            font-size: 11px;
            opacity: 0.5;
            width: 25%;
            /* height: 20px; */
            padding: 0.4%;
        }

        .parameter-td-customer-style {
            font-size: 11px;
            word-wrap: break-word;
            width: 80%;
            margin-left: 10%;
            margin-top: -35px;
            /* height: 20px; */
            padding: 0.4%;
        }

        ..invoice_table td {
            padding: 5px;
        }

        .invoice_table {
            margin-top: 1rem;
            border-collapse: collapse;
        }

        .row-bg-subtotal {
            background-color: #e8e8e8c4;
        }

        .row-white {
            background-color: #ffffff;
        }

        .td-style {
            font-size: 11px;
            line-height: 1;
            margin: 10px;
            padding: 10px;
            white-space: normal;
            vertical-align: top;
        }

        .td-style-head {
            font-size: 11px;
            opacity: 0.5;
        }

        .td-customer-style {
            font-size: 11px;
            line-height: 1;
            padding-bottom: 3px;
            padding-top: 3px;
        }

        .company_data {
            font-size: 0.8rem;
            font-weight: 400;
        }

        .td-style-gt {
            font-family: arial;
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
            border-bottom: #000000 solid 2px;
        }

        .border-t {
            border-top: #000000 solid 2px;
        }

        .border-l {
            border-left: #000000 solid 2px;
        }

        .border-r {
            border-right: #000000 solid 2px;
        }

        .brand-logo {
            width: 150px;
            padding-bottom: 20px;
            padding-top: 2px;
        }

        .heading-bg {
            background-color: #e8e8e8c4;
        }

        .heading-bg-po {
            background-color: #ffffff7d;
            color: #2b2b2b;
        }

        .total-bg {
            background-color: #e8e8e8c4;
            padding-right: 10px;
            font-family: arial;
            font-size: 10px;
            font-weight: 400;
            line-height: 20px;
            padding-left: 10px;
            padding-bottom: 5px;
        }

        .total-txt {
            text-align: left;
            padding-left: 10px;
            font-family: arial;
            font-size: 10px;
            font-weight: 400;
            line-height: 20px;
            font-weight: bold;
        }

        .total-value {
            text-align: right;
            font-family: arial;
            font-size: 10px;
            font-weight: 400;
            line-height: 20px;
            font-weight: bold;
        }

        .table-heading {
            padding-left: 15px;
            font-size: 12px;
        }

        .remark-content {
            text-align: left;
            font-size: 11px;
            text-align: justify;

            p {
                line-height: 1px;
                margin-bottom: 0px;
            }

        }

        .remark-content p {
            line-height: 1;
            margin-bottom: 0px;
            margin-top: 0px
        }

        .remark-note {
            /* text-align: center; */
            text-align: center
        }

        .section-table {
            margin-bottom: 20px;
        }

        .section-footer {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .section-table {
            margin-bottom: 20px;
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
            font-size: 17px;
        }

        .text-body {
            font-family: Cambria;
            font-size: 15px;
        }

        .text-tc {
            font-size: 12px;
            line-height: 20px;
        }

        .vendor-info {
            font-size: 0.8rem;
            line-height: 5px;
        }

        .signature-row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 3rem;
            padding: 10px 0;
            text-align: center;
        }

        .signature-label {
            margin-left: 10px;
            font-size: 11px;
        }

        .signature-dots {
            font-size: 14px;
            margin: 0 10px;
            flex-grow: 1;
        }

        .customer-section-name {
            opacity: 0.5;
            vertical-align: text-top;
        }

        .customer-section-description {
            padding-right: 10;
            vertical-align: text-top;
        }
    </style>
@endsection
