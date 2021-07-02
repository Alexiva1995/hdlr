@push('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
@endpush

@push('page_vendor_js')
<script src="{{asset('assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endpush

@push('custom_js')
<script>
    function getlink(side) {
        let dni = "{{Auth::user()->dni}}"
        console.log(dni);
        if(dni == null || dni == ""){
            toastr.error("Necesita verficarse con KYC", '¡Error!', { "progressBar": true });
            return 0;
        }
        
        var aux = document.createElement("input");
        aux.setAttribute("value", "{{route('register')}}?referred_id={{Auth::id()}}");
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        let lado = (side == 'I') ? 'Izquierda' : 'Derecha'

        Swal.fire({
            title: "Link Copiado",
            text: "Ya puedes pegarlo en su navegador",
            type: "success",
            confirmButtonClass: 'btn btn-primary',
            buttonsStyling: false,
        }).then(function(result){
                // if (result.value) {
                //     window.location.reload();
                // }
            });
    }
</script>
@endpush
