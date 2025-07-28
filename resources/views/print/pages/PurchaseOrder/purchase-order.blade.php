@extends('print.layouts.template')
@section('content')
    <div class="table-responsive">
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td style="padding-left: 0;">
                        <div class="text-head" style="margin-bottom: 10px; color: {{ $config->color_code }};"><b>PURCHASE
                                ORDER
                                - {{ $purchase_order->code }} </b></div>
                    </td>
                </tr>
                <tr>
                    <td align="left" class="td-customer-style " style="vertical-align:text-top;padding-left: 0;">
                        <div style="opacity: 0.5; padding-left: 0;">SUPPLIER</div>
                        <div style="margin-bottom: 4px;margin-top: 4px;">
                            {{ $purchase_order->supplier_name ? $purchase_order->supplier_name : '_' }}</div>
                        @isset($purchase_order->supplier_mobile)
                            <div style="margin-bottom: 4px;margin-top: 4px;">{{ $purchase_order->supplier_mobile }}</div>
                        @endisset
                        @isset($purchase_order->supplier_email)
                            <div style="margin-bottom: 4px;margin-top: 4px;">{{ $purchase_order->supplier_email }}</div>
                        @endisset
                        @isset($purchase_order->supplier_address)
                            <div style="margin-bottom: 4px;margin-top: 4px;">{{ $purchase_order->supplier_address }}</div>
                        @endisset
                    </td>
                    <td align="right" width="30%" class="td-customer-style customer-section-name">PO NO
                        <div>
                            {{-- <div style="margin-bottom: 3px;margin-top: 3px;">DATE OF ISSUE</div> --}}
                            <div style="margin-bottom: 3px;margin-top: 3px;">DATE</div>
                            {{-- <div style="margin-bottom: 3px;margin-top: 3px;"><b>DUE DATE</b></div> --}}
                    </td>
                    <td align="right" width="22%" class="td-customer-style customer-section-description">
                        <div> {{ $purchase_order->code }} </div>
                        {{-- <div style="margin-bottom: 3px;margin-top: 3px;">
                        {{ Carbon\Carbon::now()->format('d M, Y') }}
                    </div> --}}
                        <div style="margin-bottom: 3px;margin-top: 3px;">
                            {{ \Carbon\Carbon::parse($purchase_order->date)->format('d/m/Y') }}
                        </div>
                        {{-- <div style="margin-bottom: 3px;margin-top: 3px;">
                        {{ \Carbon\Carbon::parse($purchase_order->due_date)->format('d M, Y') }}
                    </div> --}}
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td align="left" class="td-customer-style nowrap"
                        style="vertical-align: top; padding: 0; width: 14%;">
                        <div style="padding-left: 0; word-wrap: break-word; word-break: break-all;">
                            @isset($purchase_order->ref_no)
                                <div style="margin-bottom: 2px; margin-top: 6px;">
                                    <span style="opacity: 0.5;">PROJECT / REF:</span>
                                </div>
                            @endisset
                        </div>
                    </td>
                    <td align="left" class="td-customer-style nowrap"
                        style="vertical-align: top; padding: 0; width: 40%;">
                        <div style="padding-left: 0; word-wrap: break-word; word-break: break-all;">
                            @isset($purchase_order->ref_no)
                                <div style="margin-bottom: 2px; margin-top: 6px;">
                                    @php
                                        $name = $purchase_order->ref_no;
                                        $lines = [];
                                        $maxLength = 40;

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
                                </div>
                            @endisset
                        </div>
                    </td>
                    <td class="td-customer-style customer-section-name" style="vertical-align: top; width: 38%;">
                        <table>
                            @foreach ($custom_fields as $custom_field)
                                <tr>
                                    <td align="right" class="td-customer-style customer-section-name">
                                        <div style="margin-top: 0px; width: 120px;">
                                            {{ $custom_field->name }}&nbsp;&nbsp;</div>
                                    </td>
                                    <td align="right" style="padding-right: 0px;"
                                        class="td-customer-style customer-section-description">
                                        <div style="width: 118px;">
                                            {{ $custom_field->description }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="purchase_order_table">
        <thead class="purchase_order_table_head" style="">
            <tr class="row-bg-head"
                style="line-height:1; white-space:nowrap; color: {{ $config->color_code }}; background-color: {{ $config->color_code }}20;">

                @if ($config->is_line_number == 1)
                    <th width="3%" style="font-size: 11px;">
                        #
                    </th>
                @endif
                <th width="45%" align="left" class="table_head_data" style="font-size: 11px;">
                    PRODUCT
                </th>
                <th width="15%" align="right" class="table_head_data " style="font-size: 11px;">
                    PRICE
                </th>
                <th width="7%" align="right" class="table_head_data " style="font-size: 11px;">
                    QTY
                </th>
                <th width="22%" align="right" class="table_head_data" style="font-size: 11px;">
                    LINE TOTAL
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase_order->purchase_order_items as $key => $item)
                <tr class="row-bg">
                    @if ($config->is_line_number == 1)
                        <td align="center" class="td-style">
                            {{ $key + 1 }}
                        </td>
                    @endif
                    <td align="left" class="td-style">
                        <p style="margin: 0px">
                            @if ($config->is_product_code == 1)
                                <span style="margin: 0px;">
                                    {!! nl2br(e($item->product_code)) !!}&nbsp;-
                                </span>
                            @endif
                            @php
                                $name = $item->product_name;
                                $lines = [];
                                $maxLength = 40;

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
                        </p>
                        @if ($config->is_product_description == 1 && $item->description)
                            <p style="margin-top: 2px; margin-bottom: 0px; font-size: 10px; color:#393939;">
                                {!! nl2br(e($item->description)) !!}
                            </p>
                        @endif
                        {{-- @if ($template->item_description == 1)
                <p style="margin: 0px;font-size:8px">
                    {!! nl2br(e($product->introduction)) !!}
                </p>
                @endif --}}
                    </td>
                    <td align="right" class="td-style right-text">
                        {{ number_format($item->cost, 2) }}
                    </td>
                    <td width="10%" align="right" class="td-style right-text">
                        {{ number_format($item->quantity, 0, '.', ',') }}
                    </td>
                    <td width="22%" align="right" class="td-style right-text">
                        {{ $purchase_order->currency_name }}
                        {{ number_format($item->total, 2) }}
                    </td>
                </tr>
            @endforeach

            <tr class="row-bg ">
                @if ($config->is_line_number == 1)
                    <td class="td-style">
                    </td>
                @endif
                <td width="10%" class="td-style right-text" style="padding-bottom: 0px; padding-top: 10px;"></td>
                <td colspan="2" align="right" class="td-style left-text"
                    style="color: #808080;white-space:nowrap; padding-bottom: 0px; padding-top: 10px; font-weight: bold;border-top: 2px dotted #eee;">
                    TOTAL</td>
                <td class="td-style right-text"
                    style="padding-bottom: 0px;white-space:nowrap; padding-top: 10px; border-top: 2px dotted #eee;">
                    {{ $purchase_order->currency_name }}
                    {{ number_format($purchase_order->total, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>
            @isset($purchase_order->note)
                <tr class="row-bg">
                    <div class="remark-content" style="remark-content"
                        style="opacity: 0.5; white-space:nowrap;margin-top: 20px; font-weight: bold; font-size: 11px;">
                        NOTE
                    </div>
                    <div class="remark-content" style="margin-top: 5px; opacity: 0.5">
                        {!! nl2br(e($purchase_order->note)) !!}
                    </div>
                </tr>
            @endisset


            @foreach ($footer_fields as $footer_field)
                <tr class="row-bg">
                    <div class="remark-content"
                        style="opacity: 0.5; text-transform: uppercase; margin-top: 20px; margin-bottom: 5px; font-weight: bold; font-size: 11px;">
                        {{ $footer_field->name }}
                    </div>
                    <div class="remark-content" style="opacity: 0.5;">
                        {!! nl2br(e($footer_field->description)) !!}
                    </div>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="remark-note"><span style="font-size:11px; margin-top:10px; opacity: 0.5;">*This is a computer generated purchase order. No signature required*</span></div> --}}

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

        .purchase_order_table th,
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

        ..purchase_order_table td {
            padding: 5px;
        }

        .purchase_order_table {
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
            margin-top: 25px;
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
