<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('images/logo-dinassosial-jakarta.webp') }}" type="image/x-icon">

        <title>{{ $metaTitle }}</title>

        @stack('meta')

        <style>
            @font-face {
                font-family: "Rc-reg";
                src: url({{ asset('fonts/RobotoCondensed-Bold.ttf') }}) format("truetype");
            }
            @font-face {
                font-family: "Qc-reg";
                src: url({{ asset('fonts/Quicksand-Regular.ttf') }}) format("truetype");
            }
            @font-face {
                font-family: "Qc-sb";
                src: url({{ asset('fonts/Quicksand-SemiBold.ttf') }}) format("truetype");
            }
        </style>

        <link rel="stylesheet" href="{{ asset('css/layouts/main.css') }}">
        @stack('css')
    </head>
    <body>

        @yield('content')

        <script>
            var BASE_URL = "{{ url('/') }}";
        </script>

        <script src="{{ asset('js/plugins/jquery.min.js') }}"></script>
        <script src="{{ asset('js/layouts/main.js') }}"></script>
        @stack('jscript')
    </body>
</html>
