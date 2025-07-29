@extends('Mails.Layouts.app')
@section('content')
<div class="report-content">
    <h4 style="color:black !important;">Monthly Business Summary Report - {{ $sendData['businessName'] }}</h4>

    <p style="color:black !important;">Dear Sir / Madam ,</p>

    <p style="color:black !important;">Here is the business summary report from {{ $sendData['start_date'] }} to {{ $sendData['end_date'] }}</p>

    <table class="border_none_table">
        <tr style="margin-bottom: 10px;">
            <td width="70%" class="vertical-align-top">Month's Total Sale:</td>
            <td style="text-align: right;" class="text-sm">
                @foreach ($sendData['monthlySail'] as $currencyCode => $sales)
                    <tr class="currency-container">
                        <td> {{ $currencyCode }}</td>
                        <td  style="text-align:right" > {{ number_format($sales, 2) }}</td>
                    </tr>
                @endforeach
        </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr style="margin-bottom: 10px;">
            <td width="70%" class="mt-2">Month's Total Income:</td>
            <td style="text-align: right;" class="text-sm">
                @foreach ($sendData['monthlyIncome'] as $currencyCode => $income)
                    <tr class="currency-container">
                        <td> {{ $currencyCode }}</td>
                        <td style="text-align:right"> {{ number_format($income, 2) }}</td>
                    </tr>
                @endforeach
        </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr style="margin-bottom: 10px;">
            <td width="70%" class="vertical-align-top mt-2">Month's Total Expense:</td>
            <td style="text-align: right;" class="text-sm">
                @foreach ($sendData['monthlyExpense'] as $currencyCode => $expense)
                    <tr class="currency-container">
                        <td> {{ $currencyCode }}</td>
                        <td style="text-align:right">{{ number_format($expense, 2) }}</td>
                    </tr>
                @endforeach
        </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr style="margin-bottom: 10px;">
            <td width="70%" class="vertical-align-top mt-2">Month's Total Invoice count:</td>
            <td style="text-align: right;" class="text-sm">
                {{ $sendData['invoice']}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr style="margin-bottom: 10px;">
            <td width="70%" class="vertical-align-top mt-2">Month's Total POS Bill count:</td>
            <td style="text-align: right;" class="text-sm">
                {{ $sendData['posBills']}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
    </table>

    <p>If you have any questions or require further information, please don't hesitate to contact us.</p>

    <p style="padding-bottom:20px" >Thank you,
        <br>
        POSByVintorr Team
    </p>
</div>
{{-- <div style="font-size:11px;text-align: center;">*This is a computer-generated report. No signature required*</div>
--}}
@endsection
<style>
    .report-content {
        font-size: 11px;
    }

    /* Define a custom table style */
    .custom-table {
        width: 75%;
        font-size: 12px;
        border-collapse: collapse;
        margin-left: 5rem !important;
    }

    .custom-table td {
        padding: 5px;
        border: 1px solid #000;
    }

    .custom-table th {
        background-color: #f2f2f2;
        padding: 5px;
        border: 1px solid #000;
    }
</style>
