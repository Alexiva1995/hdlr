<!-- Modal -->
<div class="modal fade" id="modalCierreComisionRealizado" tabindex="-1" role="dialog" aria-labelledby="modalCierreComisionRealizadoTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCierreComisionRealizadoitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 class="text-center">¿Desea volver a hacer un cierre de comisiones?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="vm_cierreComision.cerrarComisionProducto(vm_cierreComision.id, 'repetir')">Continuar</button>
            </div>
        </div>
    </div>
</div>
