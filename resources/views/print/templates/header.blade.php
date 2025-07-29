<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <script type="text/php">
        if( isset($receipt) ) {
                $font = Font_Metrics::get_font("helvetica", "bold");
                $pdf->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
            }

        </script>

    @php
        // Example aspect ratio (width/height)
        $aspect_ratio = $config->invoice_logo_ratio ?? 1;

        $image_col_percentage = 20;

        if ($aspect_ratio >= 4) {
            $image_col_percentage = 35;
        } elseif ($aspect_ratio >= 3) {
            $image_col_percentage = 30;
        } elseif ($aspect_ratio >= 2) {
            $image_col_percentage = 25;
        }

        $text_col_percentage = 100 - $image_col_percentage;
    @endphp


    <thead>
        <tr>
            <th class="ps-0" width="{{ $text_col_percentage }}%" style="text-align: left;vertical-align:text-top;">
                <div class="company_data">
                    <div class="text-head text-end" style="margin-bottom: 0px;padding-bottom:0px;">
                        @isset($config->name)
                            <b>{{ $config->name }}</b>
                        @endisset
                    </div>
                    @isset($config->address)
                        <div style="margin-bottom: 3px;margin-top: 3px;">{{ $config->address }}</div>
                    @endisset
                    @isset($config->email)
                        <div style="margin-bottom: 3px;margin-top: 3px;">{{ $config->email }}</div>
                    @endisset
                    @isset($config->mobile)
                        <div style="margin-bottom: 3px;margin-top: 3px;">{{ $config->mobile }}</div>
                    @endisset
                </div>
            </th>
            <th class="logo-area" width="{{ $image_col_percentage }}%" align="right" style="vertical-align: top;">
                @if ($config->invoice_logo_url)
                    <img src="{{ $config->invoice_logo_url }}" alt="POSByVintorr"
                        class="brand-logo me-2"
                        style="width: 100%;">
                @else
                    <img src="logo/logo.webp" alt="POSByVintorr" class="brand-logo me-2" style="width: 100%;">
                @endif
            </th>
        </tr>
    </thead>

</table>

<style>
    .row-bg-head {
        background-color: #f5f5f5;

    }

    .row-bg {
        background-color: #ffffff;
    }

    .invoice_table th,
    td {
        padding: 10px;
    }

    .invoice_table {
        margin-top: 1rem;
        border-collapse: collapse;
    }

    .row-bg-subtotal {
        background-color: #e8e8e8c4;
    }

    .company_data {
        font-size: 0.8rem;
        font-weight: 400;
    }


    .signature {
        text-align: center;
        line-height: 10px;
    }

    .signature-section {
        margin-top: 50px;
    }


    .brand-logo {
        width: 150px;
        padding-bottom: 20px;
        padding-top: 2px;
    }

    .text {
        text-align: left;
        margin-top: 20px;
        padding-bottom: 20px;
        margin-left: 20px;
    }

    .text-head {
        font-size: 17px;
    }
</style>
