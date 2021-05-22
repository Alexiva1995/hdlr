<!-- Modal -->
<div class="modal fade" id="modalEditServices" tabindex="-1" role="dialog" aria-labelledby="modalEditServicesTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditServicesTitle">Editar Servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="form form-vertical" :action="Route">
                    <div class="form-body">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Package Name</label>
                                    <input type="text" name="package_name" class="form-control" id="emojioneareaEdit" required v-model="Service.package_name">
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Elige una Categoria</label>
                                    <select name="categories_id" id="" class="form-control" required v-model="Service.categories_id">
                                        <option value="" disabled selected>Elige una opcion</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-12 col-md-6">
                                <fieldset class="form-group">
                                    <label for="">Minimum Amount</label>
                                    <input type="number" name="minimum_amount" class="form-control" required v-model="Service.minimum_amount">
                                </fieldset>
                            </div>
                            <div class="col-12 col-md-6">
                                <fieldset class="form-group">
                                    <label for="">Maximum Amount</label>
                                    <input type="number" name="maximum_amount" class="form-control" required v-model="Service.maximum_amount">
                                </fieldset>
                            </div>
                            <div class="col-12 col-md-6">
                                <fieldset class="form-group">
                                    <label for="">Precio</label>
                                    <input type="number" name="price" class="form-control" required v-model="Service.price" step="any">
                                </fieldset>
                            </div>
                            <div class="col-12 col-md-6">
                                <fieldset class="form-group">
                                    <label for="">Estado</label>
                                    <select name="status" class="form-control custom-select" v-model="Service.status">
                                        <option value="" disabled selected>Selecione una opcion</option>
                                        <option value="0">Desactivo</option>
                                        <option value="1">Activo</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Service type</label>
                                    <select name="type_services" class="form-control custom-select" required v-model="Service.type_services">
                                        <option value="" disabled selected>Selecione una opcion</option>
                                        @foreach ($types_services as $index => $value)
                                        <option value="{{$index}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-12" v-if="Service.type_services == 'default'">
                                <fieldset class="form-group">
                                    <label for="">Drip-feed</label>
                                    <select name="drip_feed" class="form-control custom-select" v-model="Service.drip_feed">
                                        <option value="" disabled selected>Selecione una opcion</option>
                                        <option value="0">Desactivo</option>
                                        <option value="1">Activo</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Type</label>
                                    <div class="d-flex">
                                        <div class="vs-radio-con vs-radio-primary">
                                            <input type="radio" name="type" value="Manual" required v-model="Service.type">
                                            <span class="vs-radio vs-radio-lg">
                                                <span class="vs-radio--border"></span>
                                                <span class="vs-radio--circle"></span>
                                            </span>
                                            <span class="">Manual</span>
                                        </div>
                                        <div class="vs-radio-con vs-radio-primary ml-2">
                                            <input type="radio" name="type" value="API" required v-model="Service.type">
                                            <span class="vs-radio vs-radio-lg">
                                                <span class="vs-radio--border"></span>
                                                <span class="vs-radio--circle"></span>
                                            </span>
                                            <span class="">API</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Input de Informacion para el servicio</label>
                                    <div class="row">
                                        <fieldset class="col-12 col-sm-6 col-md-4">
                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" name="input_adicionales[]" value="link" v-model="Service.input_adicionales">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Link</span>
                                            </div>
                                        </fieldset>
                                        <fieldset class="col-12 col-sm-6 col-md-4">
                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" name="input_adicionales[]" value="usuario" v-model="Service.input_adicionales">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Usuario</span>
                                            </div>
                                        </fieldset>
                                        <fieldset class="col-12 col-sm-6 col-md-4">
                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" name="input_adicionales[]" value="email" v-model="Service.input_adicionales">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Email</span>
                                            </div>
                                        </fieldset>
                                        <fieldset class="col-12 col-sm-6 col-md-4">
                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" name="input_adicionales[]" value="email_respaldo" v-model="Service.input_adicionales">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Email Respaldo</span>
                                            </div>
                                        </fieldset>
                                        <fieldset class="col-12 col-sm-6 col-md-4">
                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" name="input_adicionales[]" value="whatsapp" v-model="Service.input_adicionales">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Whatsapp</span>
                                            </div>
                                        </fieldset>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-12" v-if="Service.type == 'API'">
                                <fieldset class="form-group">
                                    <label for="">API Provider Name</label>
                                    <select name="api_provide_name" class="form-control custom-select" v-model="Service.api_provide_name">
                                        <option value="" disabled selected>Selecione una opcion</option>
                                        @foreach ($api_providers as $index => $value)
                                        <option value="{{$index}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <p><small>Las api no esta activa porque no esta ese modulo, esta infomacion es estatica para tener el funcionamiento de agregar servicio</small></p>
                                </fieldset>
                            </div>
                            <div class="col-12" v-if="Service.type == 'API'">
                                <fieldset class="form-group">
                                    <label for="">API ServiceID</label>
                                    <input type="text" name="api_service_id" class="form-control" v-model="Service.api_service_id">
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <label for="">Descripcion</label>
                                    <textarea name="description" class="form-control" id="summernoteEdit"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Agregar</button>
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
