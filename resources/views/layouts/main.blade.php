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
            body, html {
			    height: 100%;
		    }
            .vertical-center {
                vertical-align: middle !important;
            }
            hr.overlay {
                height: 1px;
                background-image: linear-gradient(to right,rgba(39, 65, 156, 0),rgba(39, 65, 156, 0.4),rgba(39, 65, 156, 0));
                opacity: 0.7;
            }
        </style>

        @stack('css')
    </head>
    <body>

        @yield('content')

        <script>
            var BASE_URL = "{{ url('/') }}";
        </script>

        @stack('jscript')
    </body>
</html>
