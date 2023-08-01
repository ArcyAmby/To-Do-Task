<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ToDo | Your Task Management Companion</title>

    {{-- Custom web icon --}}
    <link rel="icon" href="{{ asset('assets/img/logo.ico') }}" type="image/x-icon" />
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
</head>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
</style>

<body>
    <div id="app">
        {{-- <main-nav /> --}}
    </div>




    <script src="https://kit.fontawesome.com/3266042309.js" crossorigin="anonymous"></script>
</body>
</html>