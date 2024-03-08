<form id="frmModal">
    <div id="modalModulos" class="modal" tabindex="-1">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Nuevo modulo</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-outline">
                        <input type="number" Id= "duiUsuario" name = "duiUsuario" class="form-control" placeholder="DUI">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-outline">
                        <input type="date" class="form-control" id = "fechaUsuario" name = "fechaUsuario" placeholder="Fecha de nacimiento">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-select-control">
                        <select name="selectGenero" id="selectGenero" style = "width: 100%;">
                            <option value=""></option>
                            <option value="hombre">Hombre</option>
                            <option value="mujer">Mujer</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text" id = "primerNombreUsuario" name = "primerNombreUsuario" class="form-control" placeholder="Primer nombre">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text"  id = "segundoNombreUsuario" name = "segundoNombreUsuario" class="form-control" placeholder="Segundo nombre">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text" id = "primerApellidoUsuario" name="primerApellidoUsuario" class="form-control" placeholder="Primer apellido">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text" id="segundoApellidoUsuario" name="segundoApellidoUsuario" class="form-control" placeholder="Segundo apellido">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="email" id="correoUsuario" name="correoUsuario" class="form-control" placeholder="Correo electrónico">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-select-control">
                        <select name="selectRol" id="selectRol" style = "width: 100%;">
                            <option value=""></option>
                            <option value="1">Jefe</option>
                            <option value="2">Jefe 2</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnguardarUsuario" class="btn btn-primary">
            <i class="fas fa-save"></i>
                Guardar
            </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times-circle"></i>
                Cerrar
            </button>
        </div>
        </div>
    </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#selectGenero").select2({
            placeholder: 'Genero'
        })
        $("#selectRol").select2({
            placeholder: 'Roles'
        })

    });
</script>