<form id="frmModal">
    <div id="modalUsuario" class="modal" tabindex="-1">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Nuevo usuario</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-outline">
                        <input type="text" Id= "duiUsuario" name = "duiUsuario" class="form-control" placeholder="DUI" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-outline">
                        <input type="date" class="form-control" id = "fechaUsuario" name = "fechaUsuario" placeholder="Fecha de nacimiento" required>
                        <label for="fechaUsuario" class="input-placeholder">Fecha de nacimiento</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-select-control">
                        <select name="selectGenero" id="selectGenero" style = "width: 100%;" required>
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
                        <input type="text" id = "primerNombreUsuario" name = "primerNombreUsuario" class="form-control" placeholder="Primer nombre" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text"  id = "segundoNombreUsuario" name = "segundoNombreUsuario" class="form-control" placeholder="Segundo nombre" required>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text" id = "primerApellidoUsuario" name="primerApellidoUsuario" class="form-control" placeholder="Primer apellido" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="text" id="segundoApellidoUsuario" name="segundoApellidoUsuario" class="form-control" placeholder="Segundo apellido" required>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-outline">
                        <input type="email" id="correoUsuario" name="correoUsuario" class="form-control" placeholder="Correo electrónico" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-select-control">
                        <select name="selectRol" id="selectRol" style = "width: 100%;">
                            <option></option>
                            <?php foreach ($roles as $rol) : ?>
                                <option value="<?php echo $rol['rolId']; ?>"><?php echo $rol['rol']; ?></option>
                            <?php endforeach; ?>
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

        $('#btnguardarUsuario').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('nuevo-usuario/guardar-usuario'); ?>',
                type: 'POST',
                data: $("#frmModal").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalUsuario').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Usuario agregado con Éxito!',
                            text: response.mensaje
                        }).then((result) => {
                              // Recargar la DataTable después del insert
                        });
                        console.log("Último ID insertado:", response.empleadoId);
                    } else {
                        // Insert fallido, mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>