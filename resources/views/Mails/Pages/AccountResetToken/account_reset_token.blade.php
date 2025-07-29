@extends('Mails.Layouts.app')
@section('content')

<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
        <div class="footer-content" style="line-height: 10px !important; color:black !important;">
            <br>
            <p style="line-height: 10px !important; color:black !important;">
            <h4>Reset Your Account</h4>
            <p>You have requested to reset your POSByVintorr account.</p>
            <p>Your reset token is: <strong style="color: red;">"{{$sendData['resetToken']}}"</strong></p>
            <p>Please use this token to reset your account. If you didn't request this, you can ignore this email.</p>
            <p>If you have any questions or need assistance, please contact our support team.</p>
            <p>Thank you for using POSByVintorr!</p>
            </p>
        </div>
        <br>
    </tbody>
</table>

<div style="height: 20px;"></div>

@endsection
<style>
    .footer-content {
        text-align: left;
        font-size: 11px;
        text-align: justify;
        line-height: 8px !important;

        p {
            line-height: 5px;
            margin-bottom: 0px;
        }

    }

    .footer-content p {
        line-height: 1;
        margin-bottom: 0px;
        margin-top: 0px
    }
</style>
