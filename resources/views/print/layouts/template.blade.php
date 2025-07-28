<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SparkPOS</title>
    <style>
        .ql-cursor {
            display: none;
        }
    </style>
</head>

<body>
    <div
        style="font-family: Arial, Helvetica, sans-serif;padding-left: 20px; padding-right: 20px;padding-top: 5px;padding-bottom: 30px;">
        @include('print.templates.header')
        @yield('content')
        @include('print.templates.footer')
    </div>
</body>

</html>
