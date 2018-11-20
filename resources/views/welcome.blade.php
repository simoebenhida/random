<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NBA Score</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div id="app" class="flex flex-col min-h-screen container mx-auto items-center justify-center">
            @foreach($games as $game)
                <game :data="{{ $game }}"></game>
            @endforeach
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
