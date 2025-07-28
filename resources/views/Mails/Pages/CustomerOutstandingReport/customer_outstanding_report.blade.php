@extends('Mails.Layouts.app')
@section('content')

@if ($sendData['message'] != null && $sendData['message'] != '')
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>
            <div class="footer-content" style="line-height: 10px !important; color:black !important;">
                <br>
                <p style="line-height: 10px !important; color:black !important;" >{!! $sendData['message'] !!}</p>
            </div>
            <br>
        </tbody>
    </table>
@endif

    @if(count($sendData['invoices']) > 0)
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>
            <div class="footer-content">
                <br>
                <div class="footer-content" style="line-height: 9px !important; color:black !important; font-weight:bold; margin-bottom:10px;">Credit invoices</div>
            </div>
        </tbody>
    </table>
    <table width="100%" class="table table-bordered table_content" border="1" style="border:1px solid black;border-collapse: collapse;font-size: 11px;">
        <thead>
            <tr class="table-header" style="font-weight: 600px;background-color: #f3f2f7;">
                <th class="table-header-content" align="left" style="font-weight: 600px;padding: 5px;">Invoice No.</th>
                <th class="table-header-content" align="left" style="font-weight: 600px;padding: 5px;">Project</th>
                <th class="table-header-content text-end" align="right" style="font-weight: 600px;padding: 5px;">Days Behind</th>
                <th class="table-header-content" align="left" style="font-weight: 600px;padding: 5px;">Due Date</th>
                <th class="table-header-content text-end" align="right" style="font-weight: 600px;padding: 5px;">Due</th>
            <tr>
        </thead>
        <tbody>

        @foreach ($sendData['invoices'] as $invoice)
            <tr>
                <td class="table-data-content" align="left">{{ $invoice->code }}</td>
                <td class="table-data-content" align="left" >{{ $invoice->ref_no }}</td>
                <td class="table-data-content text-end" align="right" >
                    @php
                        $dueDate = \DateTime::createFromFormat('Y-m-d', $invoice->due_date ?? $invoice->date);
                        $diff = now()->diff($dueDate);
                        echo $diff->days;
                    @endphp
                </td>
                <td class="table-data-content" align="left"> {{ $invoice->due_date ? $invoice->due_date : '' }}</td>
                <td class="table-data-content text-end" align="right">{{$invoice->currency_name}} {{ $invoice->formatted_due }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    @if(count($sendData['credit_bills']) > 0)
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>
            <div class="footer-content">
                <br> 
               <div class="footer-content" style="line-height: 9px !important; color:black !important; font-weight:bold; margin-bottom:10px;">Credit bills</div>
            </div>
        </tbody>
    </table>
    <table width="100%" class="table table-bordered table_content" border="1" style="border:1px solid black;border-collapse: collapse;font-size: 11px;">
        <thead>
            <tr class="table-header" style="font-weight: 600px;background-color: #f3f2f7;">
                <th class="table-header-content" align="left" style="font-weight: 600px;padding: 5px;">Bill No.</th>
                <th class="table-header-content" align="left" style="font-weight: 600px;padding: 5px;">Date</th>
                <th class="table-header-content text-end" align="right" style="font-weight: 600px;padding: 5px;">Due</th>
            <tr>
        </thead>
        <tbody>

        @foreach ($sendData['credit_bills'] as $credit_bill)
            <tr>
                <td class="table-data-content" align="left">{{ $credit_bill->code }}</td></td> 
                <td class="table-data-content" align="left"> {{ $credit_bill->date }}</td>
                <td class="table-data-content text-end" align="right">{{$credit_bill->currency_name}} {{ $credit_bill->formatted_due }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
    <div style="height: 20px;"></div>
        {{-- <br><div style="font-size:11px;text-align: center;">*This is a computer generated invoice. No signature required*</div> --}}

@endsection
<style>
    .footer-content {
        text-align: left;
        font-size: 11px;
        text-align: justify;
        line-height: 8px !important;
        p{
            line-height: 5px;
            margin-bottom:0px;
        }

    }

    .footer-content p{
        line-height: 1;
        margin-bottom:0px;
        margin-top: 0px
    }
</style>
