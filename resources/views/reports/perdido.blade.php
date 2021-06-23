@extends('layouts.dashboard')

@section('content')
<div id="logs-list">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table nowrap scroll-horizontal-vertical myTable table-striped" data-order='[[ 6, "desc" ]]'>
                            <thead class="">

                                <tr class="text-center text-white bg-purple-alt2">                                
                                    <th>ID</th>
                                    <th>Correo</th>
                                    <th>Grupo - Paquete</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha de Creación</th>
                                </tr>

                            </thead>
                            <tbody>

                                @foreach ($ordenes as $orden)
                                <tr class="text-center">
                                    <td>{{$orden->id}}</td>
                                    <td>{{$orden->getOrdenUser->email}}</td>
                                    <td>{{$orden->grupo}} - {{$orden->paquete}}</td>
                                    <td>{{$orden->total}}</td>

                                    @if ($orden->status == '0')
                                    <td> <a class=" btn btn-info text-white text-bold-600">Esperando</a></td>
                                    @elseif($orden->status == '1')
                                    <td> <a class=" btn btn-success text-white text-bold-600">Aprobado</a></td>
                                    @elseif($orden->status >= '2')
                                    <td> <a class=" btn btn-danger text-white text-bold-600">Cancelado</a></td>
                                    @endif

                                    <td>{{date('Y-m-d', strtotime($orden->created_at))}}</td>
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


