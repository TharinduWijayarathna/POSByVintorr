@extends('Export.layouts.export')
@section('content')
<table>

    <thead>
        <tr>
            <th align="left" colspan="9">
                <div class="invoice_title"><b>EXPENSE REPORT</b></div>
            </th>
        </tr>
        <tr></tr>
        @if ($data['code'] != null)
            <tr>
                <th align="left">
                    <div> Code </div>
                </th>
                <th align="left">
                    <div>
                        {{ $data['code'] }}
                    </div>
                </th>
            </tr>
        @endif
        @if ($data['category'] != null)
            <tr>
                <th align="left">
                    <div> Category </div>
                </th>
                <th align="left">
                    <div>
                        {{ $data["category"]['name'] }}
                    </div>
                </th>
            </tr>
        @endif
        @if ($data["search_order_supplier"] != null)
            <tr>
                <th align="left">
                    <div> Supplier </div>
                </th>
                <th align="left">
                    <div>
                        {{ $data["search_order_supplier"] ? $data["search_order_supplier"]['name'] : '' }}
                    </div>
                </th>
            </tr>
        @endif
        @if ($data['search_order_from_date'] != null)
            <tr>
                <th align="left">
                    <div> From Date </div>
                </th>
                <th align="left">
                    <div>
                        {{ date('d/m/Y', strtotime($data['search_order_from_date'])) }}
                    </div>
                </th>
            </tr>
        @endif
        @if ($data['search_order_to_date'] != null)
            <tr>
                <th align="left">
                    <div> To Date </div>
                </th>
                <th align="left">
                    <div>
                        {{ date('d/m/Y', strtotime($data['search_order_to_date'])) }}
                    </div>
                </th>
            </tr>
        @endif
        @if ($data['currency'] != null)
            <tr>
                <th align="left">
                    <div> Currency </div>
                </th>
                <th align="left">
                    <div>{{ $data['currency']['code'] }} </div>
                </th>
            </tr>
        @endif
        <tr></tr>
        <tr>
            <th scope="col" align="left">
                <b>CODE</b>
            </th>
            <th scope="col" align="left">
                <b>CATEGORY</b>
            </th>
            <th scope="col" align="left">
                <b>SUPPLIER</b>
            </th>
            <th scope="col" align="left">
                <b>DATE</b>
            </th>
            <th scope="col" align="center">
                <b>CURRENCY</b>
            </th>
            <th scope="col" align="right">
                <b>AMOUNT</b>
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data['expenses'] as $key => $expense)
            <tr class="">
                <td align="left">
                    {{ $expense['code'] }}
                </td>

                <td align="left">
                    {{ $expense['category_name'] }}
                </td>

                <td align="left">
                    {{ $expense['supplier_name'] }}
                </td>

                <td align="left">
                    {{ date('d/m/Y', strtotime($expense['date'])) }}
                </td>

                <td align="center">
                    {{ $expense['currency_name'] }}
                </td>
                <td align="right">
                    {{ number_format($expense['amount'], 2) }}
                </td>
            </tr>
        @endforeach
        @if ($data['currency']['id'] != 0 && $data['currency'] != null)
            <tr class="">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold" align="left">TOTAL</td>
                <td style="font-weight: bold" align="right">
                    {{ number_format($data['totals']['total'], 2) }}
                </td>
            </tr>
        @endif
        <tr></tr>
        <tr>
            <th colspan="6" align="left">
                Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                SparkPos
            </th>
        </tr>
    </tbody>
</table>
@endsection
