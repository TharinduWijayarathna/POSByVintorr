@extends('print.layouts.report')
@section('content')
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th width="70%" align="left" class="customer_data">
                <div class="invoice_title" style="color:{{ $config->color_code }};">PROFIT AND LOSS REPORT</div>
            </th>
            @if ($search_data_from_date != null && $search_data_to_date != null)
        <tr>
            <div style="font-size: 11px;">From: {{ date('d/m/Y', strtotime($search_data_from_date)) }} To: {{
                date('d/m/Y', strtotime($search_data_to_date)) }}</div>
        </tr>
        @elseif ($search_data_from_date != null && $search_data_to_date == null)
        <tr>
            <div style="font-size: 11px;">From: {{ date('d/m/Y', strtotime($search_data_from_date)) }} </div>
        </tr>
        @elseif ($search_data_from_date == null && $search_data_to_date != null)
        <tr>
            <div style="font-size: 11px;">To: {{ date('d/m/Y', strtotime($search_data_to_date)) }} </div>
        </tr>
        @endif
        </tr>
        <tr>
            @if ($search_data_from_date != null && $search_data_to_date != null)
        <tr>
            <div style="font-size: 11px;">Currency: {{ $currency["code"] }}</div>
        </tr>
        @endif
        </tr>
    </thead>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="invoice_table"
    style="padding-bottom:0;margin-bottom:0;">
    <thead class="invoice_table_head ">
        <tr
            style="line-height:1; white-space:nowrap;color: {{ $config->color_code }}; background-color: {{ $config->color_code }}20;">

            <th class="table_head_data" align="left" width="75%">Sales & Income</th>
            <th class="table_head_data" width="25%"></th>
        </tr>
    </thead>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%"> Cash on Sale</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }}
            {{ number_format($sales_on_cash, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%"> Cash On Credit</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }}
            {{ number_format( $sales_on_credit, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%">Manual Transactions</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }}
            {{ number_format( $positive_manual_transactions, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td " width="75%" style="font-weight: bold;"> Total Sales</td>
        <td class="table_td " align="right" width="25%" style="font-weight: bold;">
            {{ $currency["code"] }}
            {{ number_format($sales_on_cash + $sales_on_credit + $positive_manual_transactions, 2) }}
        </td>
    </tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="invoice_table"
    style="padding-bottom:0;margin-bottom:0;">
    <thead class="invoice_table_head ">
        <tr
            style="line-height:1; white-space:nowrap;color: {{ $config->color_code }}; background-color: {{ $config->color_code }}20;">

            <th class="table_head_data" align="left" width="75%">EXPENSES</th>
            <th class="table_head_data" width="25%"></th>
        </tr>
    </thead>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%"> Returns</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }} {{ number_format($returns, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%"> Payroll</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }} {{ number_format($payroll, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%"> Expenses</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }} {{ number_format($otherExpenses, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td" width="75%">Manual Transactions</td>
        <td class="table_td" align="right" width="25%">
            {{ $currency["code"] }}
            {{ number_format( $negative_manual_transactions, 2) }}
        </td>
    </tr>
    <tr style="line-height:0.2; white-space:nowrap;">
        <td class="table_td " width="75%" style="font-weight: bold;"> Total Expenses</td>
        <td class="table_td " align="right" width="25%" style="font-weight: bold;">
            {{ $currency["code"] }} {{ number_format(($returns + $otherExpenses + $payroll +
            $negative_manual_transactions), 2) }}
        </td>
    </tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%" class="invoice_table">
    <tr
        style="line-height:1; white-space:nowrap;background-color:{{ $config->color_code  }}20;color:{{ $config->color_code  }};">
        <th class="table_head_data" width="75%" align="left"> PROFIT</th>
        <th class="table_head_data" align="right" width="25%">
            {{ $currency["code"] }} {{ number_format($profit, 2) }}
        </th>
    </tr>
    <tr class=" "
        style="line-height:1; white-space:nowrap;background-color:{{ $config->color_code  }}20;color:{{ $config->color_code }}; margin-top:5px;">
        <th class="table_head_data" width="75%" align="left"> PROFIT %</th>
        <th class="table_head_data" align="right" width="25%">{{ $currency["code"] }} {{
            number_format($profit_percentage, 2) }}%</th>
    </tr>
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

    /* .invoice_table th,
    td {
        padding: 10px;
    } */

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

    /* ..invoice_table td {
        padding: 5px;
    }

    .invoice_table {
        margin-top: 2rem;
        border-collapse: collapse;
    } */

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
        font-size: 17px;
        opacity: 0.5;
    }

    .td-style-head-value {
        font-size: 17px;
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

    .brand-logo {
        width: 100px;
    }

    .line-spacing {
        border-bottom: 1px solid black;
        border-top: 1px solid black;
    }

    .tot_bold {
        font-weight: 500 !important;
    }

    .customer_data {
        padding-left: 0rem;
    }

    .invoice_title {
        text-transform: uppercase;
        font-size: 17px;
    }

    .company_data {
        font-size: 0.8rem;
        font-weight: 400;
    }

    .page_break {
        page-break-before: always
    }

    .invoice_table {
        margin-top: 1.5rem;
        border-collapse: collapse;
    }

    .invoice_table_head {

        background-color: #f5f5f5;
        margin-top: 1.5rem;
    }

    .invoice_date {
        font-weight: 400;
        vertical-align: top;
    }

    .table_head_data {
        margin: 10px;
        padding: 10px;
        /* font-weight: 500;  */
        /* opacity: 0.3; */
        font-size: 11px;
        text-transform: uppercase;
    }

    .table_td {
        font-size: 11px;
        margin: 10px;
        padding: 10px;
    }

    .padding-key {
        padding-left: 10px;
    }

    .footer-content {
        text-align: center;
        font-size: 8px;
    }

    .footer-area {
        position: fixed;
        bottom: 5px;
    }
</style>
@endsection
