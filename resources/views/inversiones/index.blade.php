@extends('layouts.dashboard')

@section('content')
<div id="logs-list">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table w-100 nowrap scroll-horizontal-vertical myTable table-striped">
                            <thead class="">

                                <tr class="text-center text-white bg-purple-alt2">                                
                                    <th>ID</th>
                                    <th>Correo</th>
                                    <th>Paquete</th>
                                    <th>Invertido</th>
                                    <th>Ganancia</th>
                                    <th>Capital</th>
                                    <th>% Ganancia</th>
                                    <th>Ganancia acumulada</th>
                                    <th>Porcentaje fondo</th>
                                    <th>Fecha Vencimiento</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($inversiones as $inversion)

                                @php
                                $ganancia = $inversion->capital - $inversion->invertido;

                                $porcentaje = ($ganancia / $inversion->invertido) * 100;
                                @endphp
                                <tr class="text-center">
                                    <td>{{$inversion->id}}</td>
                                    <td>{{$inversion->correo}}</td>
                                    <td>{{$inversion->getPackageOrden->getGroup->name }} - {{$inversion->getPackageOrden->name}}</td>
                                    <td>$ {{number_format($inversion->invertido, 2, ',', '.')}}</td>
                                    <td>$ {{number_format($inversion->ganacia, 2, ',', '.')}}</td>
                                    <td>$ {{number_format($inversion->capital, 2, ',', '.')}}</td>
                                    <td>{{number_format($porcentaje,2, ',', '.')}} %</td>
                                    <td>$ {{number_format($inversion->ganancia_acumulada,2, ',', '.')}}</td>
                                    <td>{{number_format($inversion->porcentaje_fondo,2, ',', '.')}} %</td>
                                    <td>{{date('Y-M-d', strtotime($inversion->fecha_vencimiento))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- permite llamar a las opciones de las tablas --}}
@include('layouts.componenteDashboard.optionDatatable')


