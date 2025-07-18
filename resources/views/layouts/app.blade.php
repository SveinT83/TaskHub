<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Hub') }}</title>

        <!-- ------------------------------------------------- -->
        <!-- Fonts -->
        <!-- ------------------------------------------------- -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="css/app.css">
        
        <!-- ------------------------------------------------- -->
        <!-- Scripts -->
        <!-- ------------------------------------------------- -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @livewireStyles
    </head>
    <body>
        <div class="vh-100">
            @livewire('layouts.navigation.navigation')

            <!-- ------------------------------------------------- -->
            <!-- Page Heading -->
            <!-- ------------------------------------------------- -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="container-fluid py-3 px-4">
                        {{ $header }}

                        <!-- Widget position - header right -->
                        <div class="widget-container">
                            <livewire:widgets.position-renderer position="widget_header_right" :route="Route::currentRouteName()" />
                        </div>
                    </div>
                </header>
            @endisset

            <div class="d-flex w-100" style="min-height: calc(100vh - 60px);">

                <!-- ------------------------------------------------- -->
                <!-- Sidebar -->
                <!-- ------------------------------------------------- -->
                <div class="d-none d-md-block text-bg-dark" style="width: 250px;">
                    @livewire('layouts.navigation.sidebar-menu')

                    <!-- Widget position - sidebar -->
                    <div class="widget-container mt-3">
                        <livewire:widgets.position-renderer position="widget_sidebar" :route="Route::currentRouteName()" />
                    </div>
                </div>

                <!-- ------------------------------------------------- -->
                <!-- Page Content -->
                <!-- ------------------------------------------------- -->
                <div class="flex-grow-1">
                    <div class="container-fluid">

                        <!-- Page Header Position -->
                        <div class="row text-bg-black p-3 border-bottom justify-content-start">
                            
                            <!-- Page header from other view file -->
                            <div class="col">
                                @yield('pageHeader')
                            </div>

                            <!-- Widget position - pageHeader_right -->
                            <div class="col-auto">
                                <livewire:widgets.position-renderer position="widget_pageHeader_right" :route="Route::currentRouteName()" />
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                @include('partials.alerts')
                            </div>
                        </div>
                         @yield('content')
                    </div>
                </div>
            </div>
        </div>

        @yield('scripts') <!-- For tilpassede scripts -->
        @livewireScripts

    </body>
</html>
