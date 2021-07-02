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
                    <div class="col-12 mb-1">
                        <fieldset class="form-group text-center mb-0" style="font-size: 1.5em;">
                            <label for="" class="font-weight-bold">Saldo Final Anterior</label>
                            <div id="saldo_final_anterior" class="text-center"></div>
                        </fieldset>
                    </div>
                    <div class="col-12 mb-1">
                        <fieldset class="form-group text-center mb-0" style="font-size: 1.5em;">
                            <label for="" class="font-weight-bold">Saldo Inicial</label>
                            <div id="saldo_inicial" class="text-center"></div>
                        </fieldset>
                    </div>
                    <div class="col-12 mb-1">
                        <fieldset class="form-group text-center mb-0" style="font-size: 1.5em;">
                            <label for="" class="font-weight-bold">Ingreso</label>
                            <div id="ingreso" class="text-center"></div>
                        </fieldset>
                    </div>
                    <div class="col-12 mb-1">
                        <fieldset class="form-group text-center mb-0" style="font-size: 1.5em;">
                            <label for="" class="font-weight-bold">Saldo Final</label>
                            <div id="saldo_final" class="text-center"></div>
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
