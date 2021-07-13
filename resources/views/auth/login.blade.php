@extends('layouts.auth')

@section('content')
@push('custom_css')
<style>
    .bg-fucsia {
        background: transparent linear-gradient(90deg, #1c87fa 0%, #000733 100%) 0% 0% no-repeat padding-box;
    }

    .text-rosado {
        color: #13192E;
    }

    .btn-login {
        padding: 0.6rem 2rem;
        border-radius: 1.429rem;
    }

    .text-input-holder {
        font-weight: 800;
        color: #000000;
    }

    .card{
        border-radius: 1.5rem;
    }
</style>
@endpush
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 col-sm-8 col-12">
            {{-- header --}}
            <div class="col-12 text-center mb-5">
                <img src="{{asset('assets/img/sistema/holders-logotipo.png')}}" alt="logo" width="280">
                
            </div>
            {{-- cuerpo login --}}
            <div class="card mb-1 card-margin">
                <div class="card-header">
                    <h5 class="card-title text-center col-12 font-weight-bold" style="font-size: 2em; color:#000733;">{{ __('Iniciar Sesión') }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row" style="margin-bottom: 10px;">

                            <div class="col-md-12">
                                <input id="email" type="email"
                                    class="form-control text-input-holder @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Ingresa tu email" style="border: 1px solid black;">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password" type="password"
                                    class="form-control text-input-holder @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="current-password"
                                    placeholder="Ingresa tu contraseña" style="border: 1px solid black;">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                @if (Route::has('password.request'))
                                <a class="text-info font-italic" href="{{ route('password.request') }}" style="font-size: 0.9em; color: #1c87fa">
                                    {{ __('Olvidé mi contraseña') }}
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn bg-fucsia text-white w-75 btn-login">
                                    {{ __('Ingresar') }}
                                </button>
                            </div>
                        </div>

                        <fieldset class="checkbox mt-1 d-inline" style="font-size: 0.7em;">
                            <div class="vs-checkbox-con vs-checkbox-danger justify-content-center">
                                <input type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span class="vs-checkbox">
                                    <span class="vs-checkbox--check">
                                        <i class="vs-icon feather icon-check"></i>
                                    </span>
                                </span>
                                <span class="font-italic font-weight-bold" style="color: black;">Recordar mi contraseña</span>
                            </div>
                        </fieldset>

                        <div class="text-center font-weight-bold" style="color: black;">
                            <img src="{{asset('assets/img/sistema/icono-youtube.png')}}" alt="youtube-icon">
                            <img src="{{asset('assets/img/sistema/icono-instagram.png')}}" alt="instagram-icon">
                            <div class="font-italic" style="font-size: 0.7em;">
                                Siguenos en redes sociales.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <p class="text-center">
                    <small class="text-white font-italic">
                        <span style="font-size: 0.7em;">¿Aun no tienes una cuenta?</span>
                        <br>
                        <a class="text-white" href="{{ route('register') }}">
                            {{ __('Registrate') }}
                        </a>
                    </small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
