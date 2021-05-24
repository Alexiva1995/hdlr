<div class="row">
    <div class="col-lg-6 col-md-12 col-12 mt-1">
        <div class="card bg-analytics bg-purple-alt2 text-white h-100">
            <div class="card-content">
                <div class="card-body text-center">
                    <img src="{{asset('assets/img/sistema/ban-der.svg')}}" class="img-left" alt="card-img-left">
                    <img src="{{asset('assets/img/sistema/ban-izq.svg')}}" class="img-right" alt="card-img-right">
                    <img src="{{asset('assets/img/sistema/confe-der.svg')}}" class="img-left" alt="card-img-left"
                        style="height: 100%">
                    <img src="{{asset('assets/img/sistema/confe-izq.svg')}}" class="img-right" alt="card-img-right"
                        style="height: 100%">
                    <div class="avatar avatar-xl bg-primary shadow m-0 mb-1">
                        <img src="{{asset('assets/img/sistema/usuario.png')}}" alt="card-img-left">
                        {{-- <div class="avatar-content">
                         <i class="feather icon-award white font-large-1"></i> 
                        </div> --}}
                    </div>
                    <div class="text-center">
                        <h1 class="mb-2 text-white">Bienvenido {{$data['usuario']}}</h1>
                        <p class="m-auto w-75">
                            <a href="{{route('package.index')}}" target=""
                                class="btn btn-flat-primary padding-button-short bg-white mt-1 waves-effect waves-light">
                                Agregar Paquete
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-12 mt-1">
        <div class="card text-white bg-gradient-danger bg-red-alt h-100">
            <div class="card-content d-flex justify-contents-start align-items-center">
                <div class="card-body pb-0 pt-1">
                    <img src="{{asset('assets/img/sistema/card-img.svg')}}" alt="element 03" width="250" height="250"
                        class="float-right px-1">
                    <p class="card-text mt-3">Invita a tus amigos <br> y gana una comision</p>
                    <h4 class="card-title text-white">¡Todo es mejor con <br> amigos!</h4>
                    <div class="col-12">
                        <h5 class="text-white">Lado Activo:
                            @if (Auth::user()->binary_side_register == 'I')
                            Izquierda
                            @else
                            Derecha
                            @endif
                        </h5>
                        <h6 class="text-white">Cambiar lado</h6>
                        <a href="javascript:;" class="btn btn-flat-primary padding-button-short bg-white mt-1 waves-effect waves-light" v-on:click="updateBinarySide('D')">
                            Derecha
                        </a>
                        <a href="javascript:;" class="btn btn-flat-primary padding-button-short bg-white mt-1 waves-effect waves-light" v-on:click="updateBinarySide('I')">
                            Izquierda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
