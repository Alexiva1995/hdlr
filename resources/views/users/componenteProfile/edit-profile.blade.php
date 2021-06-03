<form action="{{ route('profile.update',$user->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PATCH') 
    <div class="media">
        <div class="custom-file">
            <label class="custom-file-label" for="photoDB">Seleccione su
                Foto <b>(Se permiten JPG o PNG.
                Tamaño máximo de 800kB)</b></label>
            <input type="file" id="photoDB"
                class="custom-file-input @error('photoDB') is-invalid @enderror"
                name="photoDB" onchange="previewFile(this, 'photo_preview')"
                accept="image/*">
            @error('photoDB')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="row mb-4 mt-4 d-none" id="photo_preview_wrapper">
        <div class="col"></div>
        <div class="col-auto">
          <img id="photo_preview" class="img-fluid rounded" />
        </div>
        <div class="col"></div>
    </div>
    
    <hr>
 
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <div class="controls">
                    <h2 class="font-weight-bold">Datos Personales</h2>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="controls">
                    <label class="required" for="">Nombre</label>
                    <input type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name" placeholder="Nombre"
                        value="{{ $user->name }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <div class="controls">
                    <label class="required" for="last_name">Apellido</label>
                    <input type="text"
                        class="form-control @error('last_name') is-invalid @enderror"
                        id="last_name" name="last_name" placeholder="Apellido"
                        value="{{ $user->last_name }}">
                    @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <div class="controls">
                    <label class="required" for="email">Email</label>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email" placeholder="Email"
                        value="{{ $user->email }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="controls">
                    <label class="required" for="whatsapp">Telefono</label>
                    <input type="text"
                        class="form-control @error('whatsapp') is-invalid @enderror"
                        name="whatsapp" value="{{ $user->whatsapp }}"
                        placeholder="whatsapp">
                    @error('whatsapp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <div class="controls">
                    <h2 class="font-weight-bold">Más Información</h2>
                </div>
            </div>
        </div>
  
        <div class="col-12">
            <div class="form-group">
                <div class="controls">
                    <label class="required" for="address">Dirección</label>
                    <textarea type="text"
                        class="form-control @error('address') is-invalid @enderror"
                        id="address"
                        name="address">{{ $user->address}}</textarea>
                    @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <div class="controls">
                    <h6 class="font-weight-bold"><span class="text-danger">Nota:
                        </span> Si no quieres añadir <span
                            class="text-danger">Más Información</span> deja
                        estos
                        espacios en blanco.</h6>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
            <button type="submit"
                class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">GUARDAR</button>
        </div>
    </div>
</form>