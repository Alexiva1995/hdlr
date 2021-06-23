var vm_cierreComision = new Vue({
    el: '#cierre_comision',
    data: function(){
        return {
            DataCierre: [],
            SaldoInicial: 0,
            id: 0
        }
    },
    computed:{
        saldoFinal: function(){
            return (parseFloat(this.SaldoInicial) + this.DataCierre.ingreso);
        }
    },
    methods: {
        /**
         * Permite Cerrar las ventas de un producto por el momento
         * @param {integer} id 
         */
        cerrarComisionProducto: function(id, repetir){
           
            if(repetir == 'repetir'){
                $('#modalCierreComisionRealizado').modal('hide') 
            }
            let url = route('commission_closing.show', id)
            axios.get(url).then((response) => {
                this.DataCierre = response.data
                this.DataCierre.package_id = id
                $('#modalCierreComision').modal('show')
            }).catch(function (error) {
                toastr.error("Ocurrio un problema con la solicitud", '¡Error!', { "progressBar": true });
            })
        },
        abrirModalCierreRealizado: function(id){
            this.id = id;
            $('#modalCierreComisionRealizado').modal('show') 
        },
    }
})