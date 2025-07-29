<table cellspacing="0" cellpadding="0" border="0" width="100%" class="system-note">
    <tbody>
        <tr class="row-bg">
            <td>
                <span class="note" style=" margin-top: 25%; ">Created at
                    {{ Carbon\Carbon::parse($created_at)->format('d/m/Y h:i A') }} | by <b>POSByVintorr</b>
                </span>
            </td>
            <td align="right">
                <span style="margin-top: 25%; opacity: 0.5;">*This is a computer generated
                    @if ($print_type == 'invoice')
                        invoice.
                    @endif
                    @if ($print_type == 'receipt')
                        receipt.
                    @endif
                    @if ($print_type == 'quotation')
                        quotation.
                    @endif
                    @if ($print_type == 'po')
                        purchase order.
                    @endif
                    @if ($print_type == 'expense')
                        voucher.
                    @endif
                    @if ($print_type == 'payroll')
                        salary slip.
                    @endif
                    No signature required*
                </span>
            </td>
            {{-- @if (isset($order) && $order['code'] != null)
                <td align="right">
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($order['code'], 'C39') }}" height="40"
                        width="150" /><br />
                </td>
            @endif
            @if (isset($quotation) && $quotation['code'] != null)
                <td align="right">
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($quotation['code'], 'C39') }}" height="40"
                        width="150" /><br />
                </td>
            @endif --}}
        </tr>
    </tbody>
</table>
<style>
    /* .brand-logo {
    text-align: center;
    vertical-align:bottom;
    margin-left: 10px;
} */
    .note {
        margin-right: 150px;
        text-align: left;
        vertical-align: middle;
    }

    .system-note {
        font-size: 8px;
        width: 100%;
        font-family: Arial, Helvetica, sans-serif;
        position: fixed;
        bottom: 0px;
        margin-bottom: -50px;
        margin-right: 50px;
    }

    .footer-note {
        font-size: 8px;
        width: 100%;
        text-align: left;
        position: fixed;
        text-align: justify;
    }

    .system-note {
        bottom: 20px;
    }
</style>
