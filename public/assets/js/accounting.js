var vm_cierreComision = new Vue({
    el: '#cierre_comision',
    data: function(){
        return {
            DataCierre: [],
            SaldoInicial: 0,
            id: 0,
            saldoAnterior: true
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
                this.DataCierre.group_id = id
                $('#modalCierreComision').modal('show')
            }).catch(function (error) {
                toastr.error("Ocurrio un problema con la solicitud", '¡Error!', { "progressBar": true });
            })
        },
        abrirModalCierreRealizado: function(id){
            this.id = id;
            $('#modalCierreComisionRealizado').modal('show') 
        },
        abrirModalCierreConfirmacion: function(){
            $('#saldo_final_anterior').text($('#formulario_saldo_final_anterior').val());
            $('#saldo_inicial').text($('#formulario_saldo_inicial').val());
            $('#ingreso').text($('#formulario_ingreso').val());
            $('#saldo_final').text($('#formulario_saldo_final').val());        

            $('#modalCierreComision').modal('hide') 
            $('#modalCierreConfirmacion').modal('show') 
        },
        submitFormulario: function(){
            $('#form_cierre').submit();
        },
        modificarSaldoAnterior: function(){
            this.saldoAnterior = !this.saldoAnterior;
        }
    }
})