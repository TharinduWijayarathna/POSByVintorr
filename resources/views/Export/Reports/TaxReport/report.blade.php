@extends('Export.layouts.export')
@section('content')
    <table class="table align-items-center mb-0" id="material-table">

        <thead>
            <tr>
                <th width="70%" align="left" class="customer_data" colspan="4" >
                    <div class="invoice_title"><b>TAX REPORT</b></div>
                </th>
            </tr>
            <tr></tr>

            <tr>
                <th>
                    <div class="table_td " align="right"> From Date </div>
                </th>
                <th>
                    <div class="table_td " align="left">
                        {{ date('d/m/Y', strtotime($data['search_order_from_date'])) }} </div>
                </th>
            </tr>
            <tr>
                <th>
                    <div class="table_td " align="right"> To Date </div>
                </th>
                <th>
                    <div class="table_td " align="left">
                        {{ date('d/m/Y', strtotime($data['search_order_to_date'])) }} </div>
                </th>
            </tr>
              @if ($data['currency'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Currency </div>
                    </th>
                    <th>
                        <div class="table_td " align="left"> {{ $data['currency']['code'] }} </div>
                    </th>
                </tr>
            @endif
              @if ($data['order_type'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Order Type </div>
                    </th>
                    <th>
                        <div class="table_td " align="left"> {{ $data['order_type']}} </div>
                    </th>
                </tr>
            @endif
              @if ($data['code'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Code </div>
                    </th>
                    <th>
                        <div class="table_td " align="left"> {{ $data['code']}} </div>
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
                    <b>CUSTOMER</b>
                </th>
                <th scope="col" align="left"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>DATE</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>TOTAL WITHOUT TAX</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>TOTAL WITH TAX</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b>TOTAL TAX</b>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['orders'] as $key => $order)
                <tr class="">
                    <td align="left">
                        {{ $order['code'] }}
                    </td>

                    <td align="left">
                        {{ $order['customer_name'] ?? 'Walking Customer' }}
                    </td>
                    <td align="left">
                        {{ date('d/m/Y', strtotime($order['date'])) }}
                    </td>
                    <td align="right">
                        {{ $order['sub_total'] }}
                    </td>
                    <td align="right">
                        {{ $order['total'] }}
                    </td>
                    <td align="right">
                        {{ $order['total_tax'] }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold" align="right">TOTAL</td>
                <td style="font-weight: bold" align="right">{{ $data['total_tax_sum'] }}</td>
            </tr>
            <tr></tr>
            <tr>
                <th width="70%" class="customer_data" colspan="4" >
                    Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                    SparkPos
                </th>
            </tr>
        </tbody>
    </table>
@endsection
