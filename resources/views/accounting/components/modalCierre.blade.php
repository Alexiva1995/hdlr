<!-- Modal -->
<div class="modal fade" id="modalCierreComision" tabindex="-1" role="dialog" aria-labelledby="modalCierreComisionTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCierreComisionTitle">Cierre del (@{{DataCierre.paquete}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="form form-vertical" action="{{route('commission_closing.store')}}" id="form_cierre">
                    <div class="form-body">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="group_id" :value="DataCierre.group_id">
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Saldo Final Anterior</label>
                                    <input type="number" step="any" class="form-control"  :readonly="saldoAnterior" :value="DataCierre.saldo_final" name="saldoFinal_anterior" id="formulario_saldo_final_anterior">
                                </fieldset>
                                @if(Auth::user()->admin == 1)
                                    <button type="button" class="btn btn-danger" id="modificar_saldo_final_anterior" onclick="vm_cierreComision.modificarSaldoAnterior()">Modificar</button>
                                @endif
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Saldo Inicial</label>
                                    <input type="number" step="any" name="s_inicial" class="form-control" required v-model="SaldoInicial" id="formulario_saldo_inicial">
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Ingreso</label>
                                    <input type="number" step="any" name="s_ingreso" class="form-control" readonly :value="DataCierre.ingreso" id="formulario_ingreso">
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Saldo Final</label>
                                    <input type="number" step="any" name="s_final" class="form-control" readonly :value="saldoFinal" id="formulario_saldo_final">
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group text-center">
                                    {{--<button type="submit" class="btn btn-primary">Guardar Cierre</button>--}}
                                    <button type="button" class="btn btn-primary" onclick="vm_cierreComision.abrirModalCierreConfirmacion()">Guardar Cierre</button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
