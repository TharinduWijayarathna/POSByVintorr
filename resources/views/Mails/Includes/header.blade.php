<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <thead>
        <tr>
            <th class="logo-area" width="20%" align="left"
                style="vertical-align: top;display: block; align-item:left; justify-content:left;">
                @if (isset($image))
                    <div style="width: 200px; height: 50px; overflow: hidden; display: flex; justify-content: left; align-items: center;" >
                        <img src="{{ $image }}" alt="" style="max-width: 100%; max-height: 100%; width: auto; height: auto"/>
                    </div>
                @else
                    <img src="{{ asset('assets/images/logos/logo.jpg') }}" alt="POSByVintorr" class="brand-logo me-2"
                        style="width: 100%; ">
                @endif
            </th>

            <th style="text-align: left;">

            </th>

            <th style="text-align: right;vertical-align:text-top;">

            </th>
        </tr>
    </thead>
</table>

<style>
</style>
