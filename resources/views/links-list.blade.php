<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Link Shortener </title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body, html {
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div id="app">
        <main-app>
            <list-page></list-page>
        </main-app>
    </div>
</body>
    <script src="{{ asset('js/app.js') }}"></script>
</html>
