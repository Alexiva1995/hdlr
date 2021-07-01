<!-- Modal -->
<div class="modal fade" id="modalCierreConfirmacion" tabindex="-1" role="dialog" aria-labelledby="modalCierreConfirmacionTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCierreConfirmacionitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 class="text-center">¿Esta seguro de realizar el cierre?</h3>
                <div class="row">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label for="">Saldo Final Anterior</label>
                            <p id="saldo_final_anterior"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label for="">Saldo Inicial</label>
                            <p id="saldo_inicial"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label for="">Ingreso</label>
                            <p id="ingreso"></p>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset class="form-group">
                            <label for="">Saldo Final</label>
                            <p id="saldo_final"></p>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="vm_cierreComision.submitFormulario()">Continuar</button>
            </div>
        </div>
    </div>
</div>
