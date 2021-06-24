@extends('layouts.dashboard')

@push('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
@endpush

@push('page_vendor_js')
<script src="{{asset('assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('assets/app-assets/vendors/js/extensions/polyfill.min.js')}}"></script>
@endpush


@section('content')
<div id="adminServices">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="m-2">
                        <a href="{{route('shop')}}" class="btn btn-primary"> Volver a los Grupos</a>
                    </div>
                    @foreach ($services->chunk(3) as $items)
                        <div class="row">
                            @foreach ($items as $product)
                            <div class="col-12 col-md-4">
                                <div class="card border-success text-center bg-transparent">
                                    <div class="card-content d-flex">
                                        <div class="card-body">
                                            <h4 class="card-title">{{$product->name}}</h4>
                                            <p class="card-text">{{$product->description}}</p>
                                            <p class="card-text">Fecha Vencimiento: <br> {{date('d-m-Y', strtotime($product->expired))}}</p>
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#modalCompra{{$product->id}}">Comprar</button>
                                            
                                                
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="modalCompra{{$product->id}}" tabindex="-1" aria-labelledby="modalCompraLabel{{$product->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCompraLabel{{$product->id}}">Compra</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <form action="{{route('shop.procces')}}" method="post" target="_blank">
                                        <div class="modal-body">
                                            @csrf
                                            <input type="hidden" name="idproduct" value="{{$product->id}}">
                                           
                                            <div class="row">
                                                <div class="col-12">
                                                    <fieldset class="form-group">
                                                        <label for="">Deposito minimo</label>
                                                        <input type="number" name="minimum_deposit" class="form-control" value="{{$product->minimum_deposit}}" disabled>
                                                    </fieldset>
                                                </div>
                                                <div class="col-12">
                                                    <fieldset class="form-group">
                                                        <label for="">Cuanto deseas depositar</label>
                                                        <input type="number" name="deposito" class="form-control" required min="{{$product->minimum_deposit}}">
                                                    </fieldset>
                                                </div>
                                            </div>
                    
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger waves-effect waves-light">Comprar</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection