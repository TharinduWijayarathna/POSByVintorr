@extends('Export.layouts.export')
@section('content')
    <table class="table align-items-center mb-0" id="material-table">

        <thead>
            <tr>
                <th width="70%" align="left" class="customer_data">
                    <div class="invoice_title"><b>PAYROLLS REPORT</b></div>
                </th>
            </tr>
            <tr></tr>
            @if ($data['code'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Code </div>
                    </th>
                    <th>
                        <div class="table_td " align="right"> {{ $data['code'] }} </div>
                    </th>
                </tr>
            @endif
            @if ($data['employee'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Employee </div>
                    </th>
                    <th>
                        <div class="table_td " align="right">
                            {{ $data['employee']
                                ? $data['employee']['name']
                                : 'Walking
                                                                            Employee' }}
                        </div>
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
            <tr></tr>
            <tr>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>CODE</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>EMPLOYEE</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>DATE</b>
                </th>
                <th scope="col" align="center"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>CURRENCY</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>AMOUNT</b>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['payrolls'] as $key => $payroll)
                <tr class="">
                    <td align="left">
                        {{ $payroll['code'] }}
                    </td>
                    <td align="left">
                        {{ $payroll['supplier_name'] }}
                    </td>
                    <td align="left">
                        {{ date('d/m/Y', strtotime($payroll['date'])) }}
                    </td>
                    <td align="center">
                        {{ $payroll['currency_name'] }}
                    </td>
                    <td align="right">
                        {{ number_format($payroll['amount'], 2) }}
                    </td>
                </tr>
            @endforeach
            @if ($data['currency'] != null)
                <tr class="">
                    <td style="font-weight: bold" align="left"></td>
                    <td style="font-weight: bold" align="left"></td>
                    <td style="font-weight: bold" align="center"></td>
                    <td style="font-weight: bold" align="right">TOTAL</td>
                    <td style="font-weight: bold" align="right">
                        {{ number_format($data['totals']['total'], 2) }}
                    </td>
                </tr>
            @endif
            <tr></tr>
            <tr>
                <th width="70%" class="customer_data">
                    Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                    POSByVintorr
                </th>
            </tr>
        </tbody>
    </table>
@endsection
