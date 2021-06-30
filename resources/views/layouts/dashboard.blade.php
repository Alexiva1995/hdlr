<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="{{ asset('assets/img/sistema/favicon.png') }}" type="image/x-icon">
    
    <title>HDLRS</title>
    {{-- Styles --}}
    @include('layouts.componenteDashboard.styles')
    {{-- Fin Styles --}}
</head>

<style>

    body::-webkit-scrollbar {
        width: 7px;
    }
    
    body::-webkit-scrollbar-thumb {
        background: #13192E;
        border-radius: 7px;
    }
    </style>
    
<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns">
    {{-- Notificaciones del sistema --}}
    @include('layouts.componenteDashboard.messageSystem')
    {{-- Notificaciones del sistema --}}
    {{-- Header --}}
    @include('layouts.componenteDashboard.header')
    {{-- Fin Header --}}
    {{-- Sidebar --}}
    @include('layouts.componenteDashboard.sidebar')
    {{-- Fin Sidebar --}}
    {{-- Cuerpo --}}
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                {{-- Migaja de pan --}}
                @if (!empty($titleg))
                @include('layouts.componenteDashboard.breadcrumb')
                @endif
                {{-- Fin Migaja de pan --}}
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    {{-- Fin Cuerpo --}}

    {{-- formulario de salir --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>

        {{-- Scritps --}}
        @routes
        @include('layouts.componenteDashboard.scripts')
        {{-- Fin Scripts --}}
</body>

</html>
