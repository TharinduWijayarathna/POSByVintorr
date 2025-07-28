@extends('Mails.Layouts.app')
@section('content')

@if ($sendData['message'] != null && $sendData['message'] != '')
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
        <div class="footer-content" style="line-height: 9px !important;margin-bottom: -20px !important;color:black !important;">
            <br>
            {!! $sendData['message'] !!}
        </div>
        <br>
    </tbody>
</table>
@endif
<br>
{{-- <div style="font-size:11px;text-align: center;color: black;">*This is a computer generated invoice. No signature
    required*</div> --}}

@endsection
<style>
    .footer-content {
        text-align: left;
        font-size: 11px;
        text-align: justify;
        line-height: 8px !important;

        p {
            line-height: 15px;
            margin-bottom: 0px;
        }

        color:black;
    }

    .footer-content p {
        line-height: 15px;
        margin-bottom: 0px;
        margin-top: 0px;
        color: black;
    }
</style>