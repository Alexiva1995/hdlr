<a onclick="tarjeta({{$data}}, '{{route('genealogy_type_id', [strtolower($type), base64_encode($data->id)])}}')">
    <img src="{{$data->logoarbol}}" alt="{{$data->name}}" title="{{$data->name}}" height="45" class="pt-1">
</a>
{{--route('genealogy_type_id', [strtolower($type), base64_encode($data->id)])--}}