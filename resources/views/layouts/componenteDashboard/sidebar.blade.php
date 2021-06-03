 <!-- BEGIN: Main Menu-->
 <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow bg-purple-alt" data-scroll-to-active="true">
    <div class="navbar-header mb-5">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand ml-1" href="{{route('home')}}">
                     <img src="{{ asset('assets/img/HDLRS--vertical-blanco.png') }}" height="150">
                     {{-- <div class="brand-logo"></div>  --}}
                    {{-- <h2 class="brand-text mb-0">Vuexy</h2> --}}
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon text-white"></i>
                    <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary text-white" data-ticon="icon-disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main bg-purple-alt mt-3" id="main-menu-navigation" data-menu="menu-navigation">
            @if (!empty($menu))
            @foreach ($menu as $index => $item)
                @if ($item['submenu'] == 0)
                <li class=" nav-item">
                    <a href="{{$item['ruta']}}{{$item['complementoruta']}}" target="{{$item['blank']}}">
                        <i class="{{$item['icon']}} text-white"></i>
                        <span class="menu-title text-white" data-i18n="{{$index}}">{{$index}}</span>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{$item['ruta']}}">
                        <i class="{{$item['icon']}} text-white"></i>
                        <span class="menu-title text-white" data-i18n="{{$index}}">{{$index}}</span>
                    </a>
                    <ul class="menu-content bg-purple-alt">
                        @foreach ($item['submenus'] as $submenu)
                        <li class="activ">
                            <a href="{{$submenu['ruta']}}{{$submenu['complementoruta']}}" class="text-white" target="{{$submenu['blank']}}">
                                <i class="feather icon-circle text-white"></i>
                                <span class="menu-item text-white" data-i18n="{{$submenu['name']}}">{{$submenu['name']}}</span></a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
            @endforeach
            @endif
        </ul>
    </div>
</div>
<!-- END: Main Menu-->