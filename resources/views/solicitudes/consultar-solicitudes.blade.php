<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <title>Consulta de solicitud</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Font -->
        <script src="https://kit.fontawesome.com/e2d71e4ca2.js" crossorigin="anonymous"></script>
        <link rel="icon" href="{{ asset('imagenes/icono_muni.png') }}">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Para lo de la bandera -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css" />

        <!-- Styles -->
        @livewireStyles
</head>

<body>
<div class="px-4 md:px-8">
<div class="max-w-6xl mx-auto mt-16 mb-6 text-center">
        


        <!-- titulo -->
        <div class="flex flex-col items-center gap-3">
                <div class="block md:hidden w-full h-1 bg-[#83BD3F]"> </div>

                <div class="flex items-center w-full">
                        <div class="flex items-center w-full">
                                <div class="hidden md:block flex-grow h-1 bg-[#83BD3F]"> </div>
                                <h1 class="
                                tracking-widest 
                                text-2xl md:text-3xl 
                                text-[#030EA7] 
                                text-center 
                                mx-auto
                                px-16
                                whitespace-normal md:whitespace-nowrap">
                                        CONSTANCIA DE RESIDENCIA 
                                </h1>

                                <div class="hidden md:block flex-grow h-1 bg-[#83BD3F]"></div>

                        </div>

                </div> 

                    <div class="block md:hidden w-full h-1 bg-[#83BD3F]"></div>

        </div>

        <p class="mt-3 text-[#4B5563] text-base md:text-lg font-bold">
                            Acá podrá verificar el estado de la solicitud de residencia que solicitó.
        </p>

       

     




        
        </div>
</div>
        
        

                <livewire:consultar-solicitud />




        @stack('modals')

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js">
        </script>
</body>
</html>