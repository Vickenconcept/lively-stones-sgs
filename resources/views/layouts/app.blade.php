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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="{{ asset('build/assets/app-NA7lqD1m.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-U_b-ZeMd.css') }}">
    <script src="{{ asset('build/assets/app-od2CeQhD.js') }}"></script>


    @yield('styles')

    {{-- @livewireStyles --}}
</head>

<body class="h-screen font-['Poppins'] ">
    <div id="app" class="h-full  text-gray-700 ">

        <x-notification />
        <x-navbar />
        <x-sidebar />
        <div id="main-section" class="h-full sm:ml-64 pt-16 bg-slate-900 relative">
            <div>
                <button id="toggle-btn" class="hidden lg:block p-2 hover:bg-white rounded-r-md cursor-pointer bg-slate-50 absolute top-6 -left-3 z-40">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                      </svg>
                      
                </button>
            </div>
            <div class="h-full md:rounded-2xl bg-slate-50 pr-1 overflow-hidden ">
                <div class="absolute inset-0  bg-no-repeat bg-fit bg-center opacity-25"
                    style="background-image: url('{{ asset('images/login-image.png') }}');">
                </div>
                <!-- Content Layer -->
                <div class="relative h-full overflow-auto">
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


    <script>
        function toggleSidebar() {
            const logoSidebar = document.getElementById('logo-sidebar');
            const mainSection = document.getElementById('main-section');

            logoSidebar.classList.toggle('hidden');
            mainSection.classList.toggle('sm:ml-64');
        }

        // Example usage on a button click
        document.getElementById('toggle-btn').onclick = toggleSidebar;
    </script>
</body>

</html>
