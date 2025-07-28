@extends('Export.layouts.export')
@section('content')
    <table class="table align-items-center mb-0" id="material-table">

        <thead>
            <tr>
                <th align="left" colspan="9">
                    <div class="invoice_title"><b>INVOICE REPORT</b></div>
                </th>
            </tr>
            <tr></tr>
            @if ($data['search_order_status'] != null)
                <tr>
                    <th align="left">
                        <div> Status </div>
                    </th>
                    <th align="left">
                        @if ($data['search_order_status'] == 1)
                            <div>
                                Paid
                            </div>
                        @elseif($data['search_order_status'] == 2)
                            <div>
                                Partial
                            </div>
                        @elseif($data['search_order_status'] == 3)
                            <div>
                                None
                            </div>
                        @else
                            <div>

                            </div>
                        @endif
                    </th>
                </tr>
            @endif
            @if ($data['invoice_no'] != null)
                <tr>
                    <th align="left">
                        <div> Invoice No </div>
                    </th>
                    <th align="left">
                        <div>
                            {{ $data['invoice_no'] }}
                        </div>
                    </th>
                </tr>
            @endif
            @if ($data['header_fields'] != null)
                <tr>
                    <th align="left">
                        <div> Header Fields </div>
                    </th>
                    <th align="left">
                        <div>
                            {{ $data['header_fields'] }}
                        </div>
                    </th>
                </tr>
            @endif
            @if ($data['search_order_customer'] != null)
                <tr>
                    <th align="left">
                        <div> Customer </div>
                    </th>
                    <th align="left">
                        <div>
                            {{ $data['search_order_customer'] ? $data['search_order_customer']['name'] : 'Walking customer' }}
                        </div>
                    </th>
                </tr>
            @endif
            @if ($data['search_order_cashier'] != null)
                <tr>
                    <th align="left">
                        <div> Created By </div>
                    </th>
                    <th align="left">
                        <div>
                            {{ $data['search_order_cashier'] ? $data['search_order_cashier']['name'] : '' }}
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
                        <div>To Date </div>
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
                        <div> {{ $data['currency']['code'] }} </div>
                    </th>
                </tr>
            @endif
            <tr></tr>
            <tr>
                <th scope="col" align="left">
                    <b>STATUS</b>
                </th>
                <th scope="col" align="left">
                    <b>INVOICE NO</b>
                </th>
                <th scope="col" align="left">
                    <b>CUSTOMER</b>
                </th>
                <th scope="col" align="left">
                    <b>CREATED BY</b>
                </th>
                <th scope="col" align="left">
                    <b>DATE</b>
                </th>
                <th scope="col" align="center">
                    <b>CURRENCY</b>
                </th>
                <th scope="col" align="right">
                    <b>TOTAL</b>
                </th>
                <th scope="col" align="right">
                    <b>PAID AMOUNT</b>
                </th>
                <th scope="col" align="right">
                    <b>DUE AMOUNT</b>
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data['invoices'] as $key => $invoice)
                <tr class="">
                    @if ($invoice['credit_status'] == 0 && $invoice['customer_paid'] > 0)
                        <td align="left">
                            PARTIAL
                        </td>
                    @elseif($invoice['status'] == 4)
                        <td align="left">
                            RETURN
                        </td>
                    @elseif($invoice['credit_status'] == 0 && $invoice['customer_paid'] == 0)
                        <td align="left">
                            NONE
                        </td>
                    @elseif($invoice['credit_status'] == 1)
                        <td align="left">
                            PAID
                        </td>
                    @else
                        <td>

                        </td>
                    @endif
                    <td align="left">
                        {{ $invoice['code'] }}
                    </td>
                    @if ($invoice['customer_id'] == 0 || $invoice['customer_id' == null])
                        <td>Walking Customer</td>
                    @else
                        <td>{{ $invoice['customer_name'] }}</td>
                    @endif

                    @if ($invoice['cashier'] != null)
                        <td>{{ $invoice['cashier_name'] }}</td>
                    @else
                        <td> </td>
                    @endif

                    <td align="left">
                        {{ date('d/m/Y', strtotime($invoice['date'])) }}
                    </td>

                    <td align="center">
                        {{ $invoice['currency_name'] }}
                    </td>

                    <td align="right">
                        {{ number_format($invoice['total'], 2) }}
                    </td>

                    <td align="right">
                        {{ number_format($invoice['customer_paid'], 2) }}
                    </td>
                    <td align="right">
                        @if ($invoice['total'] - $invoice['customer_paid'] > 0)
                            {{ number_format($invoice['total'] - $invoice['customer_paid'], 2) }}
                        @else
                            {{ number_format(0, 2) }}
                        @endif
                    </td>
                </tr>
            @endforeach
            @if ($data['currency']['id'] != 0 && $data['currency'] != null)
                <tr class="">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="font-weight: bold" align="right">TOTAL</td>
                    <td style="font-weight: bold" align="right">
                        {{ number_format($data['totals']['total'], 2) }}
                    </td>
                    <td style="font-weight: bold" align="right">
                        {{ number_format($data['totals']['paid_amount'], 2) }}
                    </td>
                    <td style="font-weight: bold" align="right">
                        {{ number_format($data['totals']['due_amount'], 2) }}
                    </td>
                </tr>
            @endif
            <tr></tr>
            <tr>
                <th colspan="9" align="left">
                    Created at {{ Carbon\Carbon::now()->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }} Prepared By
                    SparkPos
                </th>
            </tr>
        </tbody>
    </table>
@endsection
