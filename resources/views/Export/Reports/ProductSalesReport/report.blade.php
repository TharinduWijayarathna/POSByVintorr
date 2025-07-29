@extends('Export.layouts.export')
@section('content')
    <table class="table align-items-center mb-0" id="material-table">

        <thead>
            <tr>
                <th width="70%" align="left" class="customer_data" colspan="4" >
                    <div class="invoice_title"><b>PRODUCTS SALES REPORT</b></div>
                </th>
            </tr>
            <tr></tr>
            @if ($data['status'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Status </div>
                    </th>
                    <th>
                        <div class="table_td " align="left">
                            {{ $data['status']
                                ? $data['status']['name']
                                : '' }}
                        </div>
                    </th>
                </tr>
            @endif
            @if ($data['product'] != null)
                <tr>
                    <th>
                        <div class="table_td " align="right"> Product </div>
                    </th>
                    <th align="left">
                        <div class="table_td " align="left">
                            {{ $data['product']
                                ? $data['product']['name']
                                : '' }}
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
                        <div class="table_td " align="left"> {{ $data['currency']['code'] }} </div>
                    </th>
                </tr>
            @endif
            <tr>
                <th>
                    <div class="table_td " align="right"> From Date </div>
                </th>
                <th>
                    <div class="table_td " align="left">
                        {{ date('d/m/Y', strtotime($data['search_product_from_date'])) }} </div>
                </th>
            </tr>
            <tr>
                <th>
                    <div class="table_td " align="right"> To Date </div>
                </th>
                <th>
                    <div class="table_td " align="left">
                        {{ date('d/m/Y', strtotime($data['search_product_to_date'])) }} </div>
                </th>
            </tr>
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
                    <b> PRODUCT NAME</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b> TOTAL QUANTITY</b>
                </th>
                <th scope="col" align="right"
                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    style="background-color:#e1e1e1;">
                    <b> LINE TOTAL</b>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['product_items'] as $key => $product)
                <tr class="">
                    <td align="left">
                        {{ $product['product_code'] }}
                    </td>

                    <td align="left">
                        {{ $product['product']['name'] }}
                    </td>
                    <td align="right">
                        {{ $product['total_quantity'] }}
                    </td>
                    <td align="right">
                        {{ $product['total_amount'] }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="font-weight: bold" align="right">TOTAL</td>
                <td style="font-weight: bold" align="right">{{ $data['total'] }}</td>
            </tr>
            <tr></tr>
            <tr>
                <th width="70%" class="customer_data" colspan="4" >
                    Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                    POSByVintorr
                </th>
            </tr>
        </tbody>
    </table>
@endsection
