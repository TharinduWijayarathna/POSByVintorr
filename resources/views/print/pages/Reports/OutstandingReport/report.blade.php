@extends('print.layouts.report')
@section('content')
<div class="table-responsive">
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>
            <tr>
                <td style="padding-left: 0;">
                    <div class="text-head"
                        style="margin-bottom: 10px;margin-top: 10px; color: {{ $config->color_code }};"><b>OUTSTANDING
                            REPORT</b></div>
                </td>
            </tr>

        </tbody>
    </table>


    <table cellspacing="0" cellpadding="0" border="0" width="40%">
        <tbody>
            @if ($customer != null)
            <tr class="td-customer-style" style="vertical-align:text-top;padding-top: 0;margin-top: 0px">
                <div align="left" class="date-row"><span class="customer-section-name">CUSTOMER</span><span
                        style="vertical-align: text-top;;margin-left: 21px">{{ $customer ? $customer["name"] : 'Walking
                        Customer' }}</span></div>
            </tr>
            @endif
            @if ($currency != null)
            <tr class="td-customer-style" style="vertical-align:text-top;padding-top: 0;margin-top: 0px">
                <div align="left" class="date-row"><span class="customer-section-name">CURRENCY</span><span
                        style="vertical-align: text-top;;margin-left: 21px">{{ $currency["code"]}}</span>
                </div>
            </tr>
            @endif
            @if ($search_order_from_date != null)
            <tr class="td-customer-style" style="vertical-align:text-top;padding-top: 0;margin-top: 0px">
                <div align="left" class="date-row"><span class="customer-section-name">FROM
                        DATE</span><span style="vertical-align: text-top;margin-left: 20px">{{
                        \Carbon\Carbon::parse($search_order_from_date)->format('d/m/Y')
                        }}</span></div>
                <div align="left" class="date-row"><span class="customer-section-name">TO
                        DATE</span><span style="vertical-align: text-top;;margin-left: 38px">{{
                        \Carbon\Carbon::parse($search_order_to_date)->format('d/m/Y')
                        }}</span></div>
            </tr>
            @else
            <tr></tr>

            @endif
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
            <th width="10%" align="left" class="table_head_data " style="font-size: 11px;">
                DATE
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                PAID
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                0 - 30
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                31 - 60
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                61 - 90
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                OVER 90
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                AGE
            </th>
            <th width="10%" align="center" class="table_head_data" style="font-size: 11px;">
                CUR
            </th>
            <th width="10%" align="right" class="table_head_data" style="font-size: 11px;">
                TOTAL
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $key => $order)
        <tr class="row-bg">
            <td width="10%" align="left" class="td-style">
                {{ $order["code"] }}
            </td>
            <td width="10%" align="left" class="td-style nowrap">
                {{ \Carbon\Carbon::parse($order["date"])->format('d/m/Y') }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ number_format($order["customer_paid"], 2) }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ $order["age_column"] == "age_0_30" ? $order["formatted_due"] : number_format(0, 2) }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ $order["age_column"] == "age_31_60" ? $order["formatted_due"] : number_format(0, 2) }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ $order["age_column"] == "age_61_90" ? $order["formatted_due"] : number_format(0, 2) }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ $order["age_column"] == "over_90" ? $order["formatted_due"] : number_format(0, 2) }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ $order["age"] }}
            </td>
            <td width="10%" align="center" class="td-style">
                {{ $order["currency_name"] }}
            </td>
            <td width="10%" align="right" class="td-style">
                {{ number_format($order["total"], 2) }}
            </td>

        </tr>
        @endforeach
        @if ($currency)
        <tr class="row-bg " style="border-top: 2px dotted #eee;">
            <td></td>
            <td width="10%" align="right" class="td-style-total">TOTAL</td>
            <td width="10%" align="right" class="td-style-total">{{
                number_format($totals["paid_total"], 2) }}</td>
            <td width="10%" align="right" class="td-style-total">{{
                number_format($totals["age_0_30_total"], 2) }}</td>
            <td width="10%" align="right" class="td-style-total">{{
                number_format($totals["age_31_60_total"], 2) }}</td>
            <td width="10%" align="right" class="td-style-total">{{
                number_format($totals["age_61_90_total"], 2) }}</td>
            <td width="10%" align="right" class="td-style-total">{{
                number_format($totals["over_90_total"], 2) }}</td>
            <td></td>
            <td></td>
            <td width="10%" align="right" class="td-style-total">{{ number_format($totals["total"],
                2) }}</td>
        </tr>
        @endif

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
        margin-top: 2rem;
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
        white-space: nowrap;
        vertical-align: top;
    }

    .td-style-total {
        font-size: 11px;
        line-height: 1;
        margin: 10px;
        padding: 10px;
        white-space: nowrap;
        vertical-align: top;
        font-weight: bold;
        color: #252323;
    }

    .td-style-head {
        font-size: 11px;
        opacity: 0.5;
    }

    .td-customer-style {
        font-size: 11px;
        line-height: 1;
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

    .date-row {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding-top: 5px;
    }
</style>
@endsection
