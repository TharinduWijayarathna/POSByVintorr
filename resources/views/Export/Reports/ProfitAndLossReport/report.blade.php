@extends('Export.layouts.export')
@section('content')
<table class="table align-items-center mb-0" id="material-table">

    <tbody>
        <tr>
            <th width="70%" align="left" class="customer_data">
                <div class="invoice_title"><b>PROFIT AND LOSS REPORT</b></div>
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th>
                <div class="table_td " align="right"> Currency </div>
            </th>
            <th>
                <div class="table_td " align="right"> {{ $data["currency"]["code"] }} </div>
            </th>
        </tr>
        <tr>
            <th>
                <div class="table_td" align="right"> From Date</div>
            </th>
            <th>
                <div class="table_td " align="right"> {{ date('d/m/Y',
                    strtotime($data["search_data_from_date"])) }} </div>
            </th>
        </tr>
        <tr>
            <th>
                <div class="table_td " align="right"> To Date </div>
            </th>
            <th>
                <div class="table_td " align="right"> {{ date('d/m/Y',
                    strtotime($data["search_data_to_date"])) }} </div>
            </th>
        </tr>
        <tr></tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                style="background-color:#e1e1e1;">
                <b>SALES AND INCOME</b>
            </td>
            <td style="background-color:#e1e1e1;" scope="col" align="left">
                <b></b>
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Sales on Cash
            </td>
            <td align="right">
                {{ $data["sales_on_cash"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Sales on Credit
            </td>
            <td align="right">
                {{ $data["sales_on_credit"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Manual Transactions
            </td>
            <td align="right">
                {{ $data["positive_manual_transactions"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Total Sales
            </td>
            <td align="right">
                {{ ($data["sales_on_credit"] + $data["sales_on_cash"] + $data["positive_manual_transactions"]) }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                style="background-color:#e1e1e1;">
                <b>EXPENSES</b>
            </td>
            <td style="background-color:#e1e1e1;" scope="col" align="left">
                <b></b>
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Returns
            </td>
            <td align="right">
                {{ $data["returns"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Payroll
            </td>
            <td align="right">
                {{ $data["payroll"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Expenses
            </td>
            <td align="right">
                {{ $data["otherExpenses"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Manual Transactions
            </td>
            <td align="right">
                {{ $data["negative_manual_transactions"] }}
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Total Expenses
            </td>
            <td align="right">
                {{ ($data["returns"] + $data["otherExpenses"]) + $data["payroll"] +
                $data["negative_manual_transactions"] }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                style="background-color:#e1e1e1;">
                <b>PROFIT</b>
            </td>
            <td style="background-color:#e1e1e1;" scope="col" align="right">
                <b>{{ $data["profit"] }}</b>
            </td>
        </tr>
        <tr>
            <td scope="col" align="left" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                style="background-color:#e1e1e1;">
                <b>PROFIT %</b>
            </td>
            <td style="background-color:#e1e1e1;" scope="col" align="right">
                <b>{{ $data["profit_percentage"] }} %</b>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <th width="70%" class="customer_data">
                Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                POSByVintorr
            </th>
        </tr>
    </tbody>


    {{-- <tbody>
        @foreach ($data["product_items"] as $key => $product)
        <tr class="">
            <td align="right">
                {{ ++$key }}
            </td>
            <td align="left">
                {{ $product["product_code"] }}
            </td>

            <td align="left">
                {{ $product["product"]["name"] }}
            </td>
            <td align="right">
                {{ $product["total_quantity"], 0, '.', ',') }}
            </td>
            <td align="right">
                {{ $product["total_amount"] }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td align="right">Total</td>
            <td align="right">{{ $data["total"] }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th width="70%" class="customer_data">
                Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By My
                Sales
            </th>
        </tr>
    </tbody> --}}
</table>
@endsection
