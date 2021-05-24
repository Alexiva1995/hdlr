<div class="row">
    {{-- Seccion ayuda --}}
    <div class="col-12 col-md-5 mt-1">
        <div class="card h-100 d-flex justify-content-center align-items-center">
            <div class="card-content">
                <div class="card-body">
                    <div class="card-body text-center">
                        <img src="{{asset('assets/img/sistema/24-7-support.png')}}" alt="card-img-left">
                        <h4 class="card-title mt-2">
                            <strong>
                                ¿Necesitas Ayuda?
                            </strong>
                            <h4>
                                <p class="card-text">
                                    Contacta con nosotros, estaremos <br>
                                    encantado de ayudarte
                                </p>
                                <a href=""
                                    class="btn text-white padding-button-short btn-block bg-purple-alt2 mt-1 waves-effect waves-light">CONTACTANOS</a
                                    href="javascript:;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Seccion Grafico --}}
    <div class="col-12 col-md-7">
        <div class="row">
            <div class="col-sm-6 col-12 mt-1">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center text-right pb-0 pt-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="fa fa-usd text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-bold-700 mt-1">$ {{number_format($data['wallet'], '2', ',', '.')}}
                            </h2>
                            <p class="mb-0">Tu dinero</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-12 mt-1">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center text-right pb-0 pt-0">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="fa fa-money text-success font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-bold-700 mt-1">$ {{number_format($data['comisiones'], '2', ',', '.')}}</h2>
                            <p class="mb-0">Comisiones totales</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-12 mt-1">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center text-right pb-0 pt-0">
                        <div class="avatar bg-rgba-warning p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-shopping-cart text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-bold-700 mt-1">{{$data['ordenes']}}</h2>
                            <p class="mb-0">Todas las ordenes</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-4"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-12 mt-1">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center text-right pb-0 pt-0">
                        <div class="avatar bg-rgba-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="fa fa-ticket text-danger font-medium-5"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-bold-700 mt-1">{{$data['tickets']}}</h2>
                            <p class="mb-0">Total de tickets</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
