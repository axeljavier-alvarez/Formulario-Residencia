
@props(['breadcrumb' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('imagenes/icono_muni.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Font awesome -->
        <script src="https://kit.fontawesome.com/e2d71e4ca2.js" crossorigin="anonymous"></script>

        @stack('css')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
</head>

<body
class="pt-10"
x-data="{ open: false }"
:class="{ 'overflow-hidden': open }"
class="sm:overflow-auto"
>

   
@include('layouts.includes.interno.navigation')
@include('layouts.includes.interno.sidebar')

<div class="p-4 sm:ml-64">

   <div class="mt-14">

   @include('layouts.includes.interno.breadcrumb')

   {{-- <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700"> --}}
   <div class="p-4">
      <div class="w-full">

        {{ $slot }}
      </div>
   </div>
   </div>

</div>


<div x-cloak
x-show="open"
x-on:click="open = false"
 class="bg-gray-900 bg-opacity-50 fixed inset-0 z-30 sm:hidden"

>
</div>




@stack('modals')

        @livewireScripts
</body>
</html>