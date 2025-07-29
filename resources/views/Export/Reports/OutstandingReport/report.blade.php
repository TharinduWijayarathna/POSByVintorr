@extends('Export.layouts.export')
@section('content')
<table>

    <thead>
        <tr>
            <th align="left" colspan="11">
                <div class="invoice_title"><b>OUTSTANDING REPORT</b></div>
            </th>
        </tr>
        <tr></tr>
        @if ($data["code"] != null)
            <tr>
                <th align="left">
                    <div> Code </div>
                </th>
                <th align="left">
                    <div> {{ $data["code"]  }} </div>
                </th>
            </tr>
        @endif
        @if ($data["customer"] != null)
            <tr>
                <th>
                    <div align="right"> Customer </div>
                </th>
                <th>
                    <div align="right"> {{ $data["customer"] ? $data["customer"]["name"] : 'Walking
                        Customer' }} </div>
                </th>
            </tr>
        @endif
        @if ($data["currency"] != null)
            <tr>
                <th>
                    <div align="right"> Currency </div>
                </th>
                <th>
                    <div align="right"> {{ $data["currency"]["code"] }} </div>
                </th>
            </tr>
        @endif
        <tr>
            <th>
                <div align="right"> From Date </div>
            </th>
            <th>
                <div align="right"> {{ date(
        'd/m/Y',
        strtotime($data["search_order_from_date"])
    ) }} </div>
            </th>
        </tr>
        <tr>
            <th>
                <div align="right"> To Date </div>
            </th>
            <th>
                <div align="right"> {{ date(
        'd/m/Y',
        strtotime($data["search_order_to_date"])
    ) }} </div>
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th align="left" style="background-color:#e1e1e1;">
                <b>CODE</b>
            </th>
            <th align="left" style="background-color:#e1e1e1;">
                <b>DATE</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b>PAID AMOUNT</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b> 0 - 30 DAYS</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b> 31- 60 DAYS</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b> 61 - 90 DAYS</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b> Over 90 DAYS</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b>AGE (DAYS)</b>
            </th>
            <th align="center" style="background-color:#e1e1e1;">
                <b>CURRENCY</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b>TOTAL</b>
            </th>
            <th align="right" style="background-color:#e1e1e1;">
                <b>DUE AMOUNT</b>
            </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data["orders"] as $key => $order)
            <tr>
                <td align="left">
                    {{ $order["code"] }}
                </td>
                <td align="left">
                    {{ date('d/m/Y', strtotime($order["date"])) }}
                </td>
                <td align="right">
                    {{ $order["customer_paid"]}}
                </td>
                <td align="right">
                    {{ $order["age_column"] == "age_0_30" ? $order["formatted_due"] : 0 }}
                </td>
                <td align="right">
                    {{ $order["age_column"] == "age_31_60" ? $order["formatted_due"] : 0 }}
                </td>
                <td align="right">
                    {{ $order["age_column"] == "age_61_90" ? $order["formatted_due"] : 0 }}
                </td>
                <td align="right">
                    {{ $order["age_column"] == "over_90" ? $order["formatted_due"] : 0 }}
                </td>
                <td align="right">
                    {{ $order["age"] }}
                </td>
                <td align="center">
                    {{ $order["currency_name"] }}
                </td>
                <td align="right">
                    {{ $order["total"]}}
                </td>
                <td align="right">
                    {{ $order["total"] - $order["customer_paid"] }}
                </td>
            </tr>
        @endforeach
        @if ($data["currency"] != null)
            <tr>
                <td style="font-weight: bold" align="left"></td>

                <td style="font-weight: bold" align="right">TOTAL</td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["paid_total"] }}
                </td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["age_0_30_total"] }}
                </td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["age_31_60_total"] }}
                </td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["age_61_90_total"] }}
                </td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["over_90_total"] }}
                </td>
                <td style="font-weight: bold" align="right"></td>
                <td style="font-weight: bold" align="center"></td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["total"] }}
                </td>
                <td style="font-weight: bold" align="right">
                    {{ $data["totals"]["outstanding_total"] }}
                </td>
            </tr>
        @endif
        <tr></tr>
        <tr>
            <th colspan="11">
                Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                POSByVintorr
            </th>
        </tr>
    </tbody>
</table>
@endsection
