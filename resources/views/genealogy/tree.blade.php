@extends('layouts.dashboard')

@section('title', $type)

@push('custom_css')
<link rel="stylesheet" href="{{asset('assets/css/tree2.css')}}">
@endpush

@section('content')
<div class="col-12 text-center">
    <div class="padre">
        <ul>
            <li class="baseli">
                <a class="base" href="#">
                    <img src="{{$base->logoarbol}}" alt="{{$base->name}}" title="{{$base->name}}" height="82" class="pt-1">
                </a>
                {{-- Nivel 1 --}}
                <ul>
                    @foreach ($trees as $child)
                    <li>
                        @include('genealogy.component.subniveles', ['data' => $child])
                        @if (!empty($child->children))
                        {{-- nivel 2 --}}
                        <ul>
                            @foreach ($child->children as $child)
                            <li>
                                @include('genealogy.component.subniveles', ['data' => $child])
                                @if (!empty($child->children))
                                {{-- nivel 3 --}}
                                <ul>
                                    @foreach ($child->children as $child)
                                    <li>
                                        @include('genealogy.component.subniveles', ['data' => $child])
                                        @if (!empty($child->children))
                                        {{-- nivel 4 --}}
                                        <ul>
                                            @foreach ($child->children as $child)
                                            <li>
                                                @include('genealogy.component.subniveles', ['data' => $child])
                                                @if (!empty($child->children))
                                                {{-- nivel 5 --}}
                                                <ul>
                                                    @foreach ($child->children as $child)
                                                    <li>
                                                        @include('genealogy.component.subniveles', ['data' => $child])
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                {{-- fin nivel 5 --}}
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        {{-- fin nivel 4 --}}
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                {{-- fin nivel 3 --}}
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        {{-- fin nivel 2 --}}
                        @endif
                    </li>
                    @endforeach
                </ul>
                {{-- fin nivel 1 --}}
            </li>
        </ul>
    </div>
    @if (Auth::id() != $base->id)
    <div class="col-12 text-center">
        <a class="btn btn-info" href="{{route('genealogy_type', strtolower($type))}}">Regresar a mi arbol</a>
    </div>
    @endif
</div>
@endsection
