@extends('print.layouts.template')
@section('content')
    <div class="table-responsive">
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td style="padding-left: 0;">
                        <div class="text-head" style="margin-bottom: 10px; color: {{ $config->color_code }};"><b>RECEIPT</div>
                    </td>
                </tr>
                <tr>
                    <td align="left" class="td-customer-style " style="vertical-align:text-top;padding-left: 0;">
                        <div style="opacity: 0.5;padding-left: 0;">PAYED BY</div>
                        <div style="margin-bottom: 4px;margin-top: 4px;">
                            {{ $order->customer ? $order->customer_name : 'Walking Customer' }}</div>
                        @isset($order->customer_mobile)
                            <div style="margin-bottom: 4px;margin-top: 4px;">{{ $order->customer_mobile }}</div>
                        @endisset
                        @isset($order->customer_email)
                            <div style="margin-bottom: 4px;margin-top: 4px;">{{ $order->customer_email }}</div>
                        @endisset
                        @isset($order->customer_address)
                            <div style="margin-bottom: 4px;margin-top: 4px;">{{ $order->customer_address }}</div>
                        @endisset
                        @isset($order->ref_no)
                            <div style="margin-bottom: 4px;margin-top: 10px;">PROJECT / REF: {{ $order->ref_no }}</div>
                        @endisset

                    </td>
                    {{-- <td align="right" class="td-customer-style " style="opacity: 0.5; vertical-align:text-top;">
                        <div>INVOICE NO.<div>
                                <div style="margin-bottom: 3px;margin-top: 3px;">DATE OF ISSUE</div>
                                <div style="margin-bottom: 3px;margin-top: 3px;">PAYMENT DATE</div>
                            </div>

                            @if ($order->customer_paid > 0)
                                    <div style="margin-bottom: 3px;margin-top: 10px; font-weight: bold;">INVOICE TOTAL
                                    </div>
                                @else
                                    <div style="margin-top: 8px;">
                                    </div>
                                @endif

                                <div style="margin-bottom: 3px;margin-top: 3px; font-weight: bold">DUE AMOUNT
                                </div>
                    </td>
                    <td align="right" width="13%" class="td-customer-style "
                        style="padding-right: 0;vertical-align:text-top;">
                        <div> {{ $order->code }} </div>
                        <div style="margin-bottom: 3px;margin-top: 3px;">
                            {{ Carbon\Carbon::now()->format('d M, Y') }}</div>
                        <div style="margin-bottom: 3px;margin-top: 3px;">
                            {{ \Carbon\Carbon::parse($receipt->date)->format('d M, Y') }}</div>

                        @if ($order->customer_paid > 0)
                            <div style="margin-bottom: 3px;margin-top: 10px;white-space:nowrap; font-weight: bold;">
                                {{ $order->currency_name }}
                                {{ number_format($order->customer_paid, 2) }}
                            </div>
                        @else
                            <div style="margin-top: 8px;">
                            </div>
                        @endif
                        <div style="margin-bottom: 3px;margin-top: 3px;white-space:nowrap; font-weight: bold;">

                            {{ $order->currency_name }}
                            @if ($order->total - $order->customer_paid == 0)
                                0.00
                            @else
                                {{ number_format($order->total - $order->customer_paid, 2) }}
                            @endif

                        </div>
                    </td> --}}
                </tr>
            </tbody>
        </table>
    </div>


    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="invoice_table">
        <thead class="invoice_table_head" style="">
            <tr class="row-bg-head"
                style="line-height:1; white-space:nowrap; color: {{ $config->color_code }}; background-color: {{ $config->color_code }}20;">

                <th width="40%" align="left" class="table_head_data" style="font-size: 11px;">
                    DATE
                </th>
                <th width="40%" align="left" class="table_head_data" style="font-size: 11px;">
                    INVOICE
                </th>
                <th width="20%" align="right" class="table_head_data " style="font-size: 11px;">
                    PAYMENT
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="row-bg">
                <td align="left" class="td-style">
                    <p style="margin: 0px">
                        {{ \Carbon\Carbon::parse($receipt->date)->format('d/m/Y') }}
                    </p>
                </td>
                <td align="left" class="td-style">
                    <p style="margin: 0px">
                        {{ $order->code }}
                    </p>
                </td>
                <td align="right" class="td-style right-text" style="white-space:nowrap;">
                    {{ $order->currency_name }}
                    {{ number_format($receipt->accepted_amount, 2) }}
                </td>
            </tr>
            <tr class="row-bg " style="border-top: 2px dotted #eee;">
                <td colspan="2" align="right" class="td-style left-text"
                    style="color: #808080; padding-bottom: 0px; padding-top: 5px;">INVOICE AMOUNT</td>
                <td class="td-style right-text" style="white-space:nowrap; padding-bottom: 0px; padding-top: 5px;">
                    {{ $order->currency_name }}
                    {{ number_format($order->total, 2) }}
                </td>
            </tr>
            <tr class="row-bg " style="border-top: 2px dotted #eee;">
                <td colspan="2" align="right" class="td-style left-text"
                    style="color: #808080; padding-bottom: 0px; padding-top: 5px;">TOTAL PAID AMOUNT</td>
                <td class="td-style right-text" style="white-space:nowrap; padding-bottom: 0px; padding-top: 5px;">
                    {{ $order->currency_name }}
                    {{ number_format($order->customer_paid, 2) }}
                </td>
            </tr>
            @if ($order->total - $order->customer_paid > 0)
                <tr class="row-bg " style="border-top: 2px dotted #eee;">
                    <td colspan="2" align="right" class="td-style left-text"
                        style="color: #808080; padding-bottom: 0px; padding-top: 5px;">DUE AMOUNT</td>
                    <td class="td-style right-text" style="white-space:nowrap; padding-bottom: 0px; padding-top: 5px;">
                        {{ $order->currency_name }}
                        {{ number_format($order->total - $order->customer_paid, 2) }}
                    </td>
                </tr>
            @endif
            {{-- @if ($receipt->change > 0)
            <tr class="row-bg " style="border-top: 2px dotted #eee;">
                <td class="right-text" style="padding-bottom: 0px; padding-top: 5px;"></td>
                <td colspan="2" align="right" class="td-style left-text"
                    style="color: #808080; padding-bottom: 0px; padding-top: 5px;">CHANGE</td>
                <td class="td-style right-text" style="white-space:nowrap; padding-bottom: 0px; padding-top: 5px;">
                    {{ $order->currency_name }}
                    {{ number_format($receipt->change, 2) }}
                </td>
            </tr>
            @endif --}}
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>

            @if ($receipt->remark != null)
                <tr class="row-bg">
                    <div
                        style="opacity: 0.5; white-space: pre-line; text-transform: uppercase; margin-top: 40px; font-weight: bold; font-size: 11px; padding-left: 0;">
                        REMARK</div>
                    <div class="remark-content" style="opacity: 0.5;">
                        {{ $receipt->remark }}
                    </div>
                </tr>
            @endif
            <tr class="row-bg">
                <div class="remark-content" style="font-size: 12px; padding-top: 50px; text-align: center; opacity: 0.5;">
                    Thank you for your payment!
                </div>
            </tr>
        </tbody>
    </table>

    {{-- <div class="signature-row">
        @if ($template->prepared_by == 1)
            <div style="display: inline-block; margin-right: 20px;">
                <div><span class="signature-dots">..............................</span></div>
                <span class="signature-label">Prepared by</span>
            </div>
        @endif

        @if ($template->checked_by == 1)
            <div style="display: inline-block; margin-right: 20px;">
                <div><span class="signature-dots">..............................</span></div>
                <span class="signature-label">Checked by</span>
            </div>
        @endif

        @if ($template->received_name == 1)
            <div style="display: inline-block; margin-right: 20px;">
                <div><span class="signature-dots">..............................</span></div>
                <span class="signature-label">Received by</span>
            </div>
        @endif

        @if ($template->received_id_no == 1)
            <div style="display: inline-block;">
                <div><span class="signature-dots">..............................</span></div>
                <span class="signature-label">Received ID</span>
            </div>
        @endif
    </div> --}}



    {{-- @if ($template->prepared_by !== 1 && $template->checked_by !== 1 && $template->received_name !== 1 && $template->received_id_no !== 1)
        <div class="remark-note"><span style="font-size:11px; color: #808080;">*This is a computer generated invoice. No
                signature required*</span></div>
    @endif
    @if ($invoice->status == 0)
        <style>
            body {
                background-image: url(img/draft.png);
                background-size: cover;
                background-repeat: no-repeat;
                z-index: 999;
            }
        </style>
    @endif
    @if ($invoice->deleted_at != null)
        <style>
            body {
                background-image: url(img/deleted.png);
                background-size: cover;
                background-repeat: no-repeat;
                z-index: 999;
            }
        </style>
    @endif --}}
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
    </style>
@endsection
