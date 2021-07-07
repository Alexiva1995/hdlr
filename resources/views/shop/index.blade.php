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
                    <div class="row">
                    @foreach ($categories->chunk(3) as $items)
                        
                            @foreach ($items as $grupo)
                            <div class="col-12 col-md-4">
                                <a href="{{route('shop.products', $grupo->id)}}">
                                    <div class="card text-white">
                                        <img class="card-img" src="{{asset('storage/'.$grupo->img)}}" alt="{{$grupo->name}}">
                                        <div class=" ">
                                            <div style="position: relative; top:100%; width: 100%;">
                                            <h4 class="card-title text-white text-center bg-primary d-block" style="font-size: 2em;">{{$grupo->name}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach                            
                        
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection