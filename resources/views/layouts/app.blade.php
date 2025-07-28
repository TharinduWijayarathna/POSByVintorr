<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>SparkPOS | Affordable, Simplified Point of Sale System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="keywords" content="SparkPos, Affordable, Simplified Point of Sale System, @yield('meta-keywords', 'Transform your retail success with SparkPos - the affordable, user-friendly POS solution for simplified management and enhanced customer experiences.')">
    <meta name="description" content="@yield('og-description', 'Transform your retail success with SparkPos - the affordable, user-friendly POS solution for simplified management and enhanced customer experiences.')" />

    <meta name="og:image" property="og:image" content="@yield('og-image', asset('assets/images/logos/logo.webp'))" />
    <meta name="og:image:secure_url" property="og:image:secure_url" content="@yield('og-image', asset('assets/images/logos/logo.webp'))" />
    <meta name="og:image:width" property="og:image:width" content="@yield('ogimagewidth', 500)" />
    <meta name="og:image:height" property="og:image:height" content="@yield('ogimageheight', 200)" />
    <meta name="og:image:alt" property="og:image:alt"
        content="SparkPos | Affordable, Simplified Point of Sale System" />
    <meta name="og:site_name" property="og:site_name" content="Affordable, Simplified Point of Sale System" />
    <meta name="og:type" property="og:type" content="@yield('og-type', 'website')" />
    <meta name="og:title" property="og:title" content="@yield('og-title', 'SparkPos | Affordable, Simplified Point of Sale System')" />
    <meta name="og:url" property="og:url" content="{{ Request::url() }}" />
    <meta name="og:description" property="og:description" content="@yield('og-description', 'Transform your retail success with SparkPos - the affordable, user-friendly POS solution for simplified management and enhanced customer experiences.')" />

    <meta name="twitter:image:src" content="@yield('og-image', asset('assets/images/logos/logo.webp'))" />
    <meta name="twitter:site" content="@Affordable, Simplified Point of Sale System" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('og-title', 'SparkPos | Affordable, Simplified Point of Sale System')" />
    <meta name="twitter:description" content="@yield('og-description', 'Transform your retail success with SparkPos - the affordable, user-friendly POS solution for simplified management and enhanced customer experiences.')" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/logo_favicon.webp') }}">

    @include('libraries.styles')
</head>

<body>
      <!-- Loader -->
      <div id="preloader" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div id="status" style="text-align: center;">
            <img src="assets/images/logos/logo.webp" alt="Your Logo" class="blinking-image" style="max-width: 100px; max-height: 100px;">
        </div>
    </div>


<!-- Loader -->
    @include('components.header')

    @yield('content')

    @include('components.footer')

    @include('libraries.scripts')
</body>

</html>
