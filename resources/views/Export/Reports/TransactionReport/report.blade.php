@extends('Export.layouts.export')
@section('content')
    <table class="table align-items-center mb-0" id="material-table">

        <thead>
            <tr>
                <th width="70%" align="left" class="customer_data">
                    <div class="invoice_title"><b>TRANSACTIONS REPORT</b></div>
                </th>
            </tr>
            <tr></tr>
            @if ($data['type'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Type </div>
                    </th>
                    <th>
                        @if ($data['type'] == 1)
                            <div class="table_td " align="right"> Bill </div>
                        @endif
                        @if ($data['type'] == 2)
                            <div class="table_td " align="right"> Invoice </div>
                        @endif
                        @if ($data['type'] == 3)
                            <div class="table_td " align="right"> Expense </div>
                        @endif
                        @if ($data['type'] == 4)
                            <div class="table_td " align="right"> Return </div>
                        @endif
                        @if ($data['type'] == 5)
                            <div class="table_td " align="right"> Manual </div>
                        @endif
                    </th>
                </tr>
            @endif
            @if ($data['search_transaction_type'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Transaction Type </div>
                    </th>
                    <th>
                        @if ($data['search_transaction_type'] == 1)
                            <div class="table_td " align="right"> In </div>
                        @endif
                        @if ($data['search_transaction_type'] == 2)
                            <div class="table_td " align="right"> Out </div>
                        @endif
                    </th>
                </tr>
            @endif
            @if ($data['currency'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Currency </div>
                    </th>
                    <th>
                        <div class="table_td " align="right"> {{ $data['currency']['code'] }} </div>
                    </th>
                </tr>
            @endif
            @if ($data['search_from_date'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> From Date </div>
                    </th>
                    <th>
                        <div class="table_td " align="right">
                            {{ date('d/m/Y', strtotime($data['search_from_date'])) }} </div>
                    </th>
                </tr>
            @endif
            @if ($data['search_to_date'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> To Date </div>
                    </th>
                    <th>
                        <div class="table_td " align="right">
                            {{ date('d/m/Y', strtotime($data['search_to_date'])) }} </div>
                    </th>
                </tr>
            @endif
            @if ($data['code'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> REF Code </div>
                    </th>
                    <th>
                        <div class="table_td " align="right">
                            {{ $data['code'] }} </div>
                    </th>
                </tr>
            @endif
            @if ($data['client'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Customer / Supplier </div>
                    </th>
                    <th>
                        <div class="table_td " align="right">
                            {{ $data['client'] }} </div>
                    </th>
                </tr>
            @endif
            <tr></tr>
            <tr>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>Type</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>DATE</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>CODE</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>CUSTOMER / SUPPLIER</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>DESCRIPTION</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>AMOUNT</b>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['transactions'] as $key => $transaction)
                <tr class="">
                    @if ($transaction['type'] == 1)
                        <td align="left">
                            Bill
                        </td>
                    @endif
                    @if ($transaction['type'] == 2)
                        <td align="left">
                            Invoice
                        </td>
                    @endif
                    @if ($transaction['type'] == 3)
                        <td align="left">
                            Expense
                        </td>
                    @endif
                    @if ($transaction['type'] == 4)
                        <td align="left">
                            Return
                        </td>
                    @endif
                    @if ($transaction['type'] == 5)
                        <td align="left">
                            Manual
                        </td>
                    @endif
                    <td align="left">
                        {{ date('d/m/Y', strtotime($transaction['date'])) }}
                    </td>
                    <td align="left">
                        {{ $transaction['reference_code'] }}
                    </td>
                    <td align="left">
                        {{ $transaction['client'] }}
                    </td>
                    <td align="left">
                        {{ $transaction['description'] }}
                    </td>
                    <td align="right">
                        @if ($transaction['sign'] == 0)
                            -
                        @endif
                        @if ($transaction['sign'] == 1)
                            +
                        @endif
                        &nbsp;
                        {{ $transaction['currency_name'] }}
                        &nbsp;
                        {{ number_format($transaction['amount'], 2) }}
                    </td>
                </tr>
            @endforeach
            <tr></tr>
            <tr>
                <th width="70%" class="customer_data">
                    Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                    SparkPos
                </th>
            </tr>
        </tbody>
    </table>
@endsection
