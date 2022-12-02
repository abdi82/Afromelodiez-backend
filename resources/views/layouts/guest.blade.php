<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Afromelodiez') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.js" defer></script>
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            <div style="    display: flex;
    justify-content: end;
    padding: 15px;">
                @if(\Request::getRequestUri() == '/login')
                    <a href="/register"> <x-jet-button class="ml-4">

                            {{ __('Artist Register') }}

                        </x-jet-button></a>
                @elseif(\Request::getRequestUri() == '/register')
                    <a href="/login">  <x-jet-button class="ml-4">

                            {{ __('Login') }}

                        </x-jet-button>  </a>
                @endif
            </div>
            {{ $slot }}
        </div>
    </body>
</html>
