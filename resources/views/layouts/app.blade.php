<!DOCTYPE html>
<html lang="en" class="h-full bg-white ">

<head>
    {{-- <x-seo::meta /> --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @seo([
        'title' => 'Video',
        'description' => 'Influencers Management Hub',
        'image' => asset('images/login-image.png'),
        'site_name' => config('app.name'),
        'favicon' => asset('images/fav-image.png'),
    ]) --}}

    <title>Lively Stones</title>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    {{-- <script src="https://unpkg.com/@alpinejs/focus" defer></script> --}}

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script> --}}


    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @yield('styles')

    {{-- @livewireStyles --}}
</head>

<body class="h-screen font-['Poppins'] ">
    <div id="app" class="h-full  text-gray-700 ">

        <x-notification />
        <x-navbar />
        <x-sidebar />
        <div class="h-full sm:ml-64 pt-16 bg-slate-900 ">
            <div class="h-full md:rounded-2xl bg-slate-50 pr-1 overflow-hidden">
                <div class="h-full overflow-auto">
                    <x-session-msg />
                    @yield('content')
                </div>
            </div>
            {{-- {{ $slot }} --}}
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')

    {{-- @livewireScripts --}}

</body>

</html>
