<form id="frmModal" method="post" action="<?php echo base_url('conf-general/admin-usuarios/operacion/usuarios'); ?>">
    <input type="hidden" id="usuarioId" name="usuarioId" value="<?= $usuarioId; ?>">
    <input type="hidden" id="empleadoId" name="empleadoId" value="<?= $empleadoId; ?>">
    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
    <div id="modalUsuario" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar usuario' : 'Nuevo usuario'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" Id="duiUsuario" name="duiUsuario" class="form-control" placeholder="DUI" value="<?= $campos['dui']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <div class="input-fiel">
                                    <input type="date" class="form-control" id="fechaUsuario" name="fechaUsuario" placeholder="Fecha de nacimiento" value="<?= $campos['fechaNacimiento']; ?>"  required>
                                </div>
                            </div>
                            <small>Fecha de nacimiento</small>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectGenero" id="selectGenero" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="primerNombreUsuario" name="primerNombreUsuario" class="form-control" placeholder="Primer nombre" value="<?= $campos['primerNombre']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="segundoNombreUsuario" name="segundoNombreUsuario" class="form-control" placeholder="Segundo nombre" value="<?= $campos['segundoNombre']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="primerApellidoUsuario" name="primerApellidoUsuario" class="form-control" placeholder="Primer apellido" value="<?= $campos['primerApellido']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="segundoApellidoUsuario" name="segundoApellidoUsuario" class="form-control" placeholder="Segundo apellido" value="<?= $campos['segundoApellido']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label>¿Desearia crear usuario?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="radioCrear" id="radioCrearSi" value="si">
                                <label class="form-check-label" for="radioCrearSi">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="radioCrear" id="radioCrearNo" value="no" checked>
                                <label class="form-check-label" for="radioCrearNo">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4" id="divCorreoUsuario">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="email" id="correoUsuario" name="correoUsuario" class="form-control" placeholder="Correo electrónico"  value="<?= $campos['correo']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectRol" id="selectRol" style="width: 100%;">
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
                    <button type="submit" id="btnguardarUsuario" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="$('#modalUsuario').modal('hide');">
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

        $('#divCorreoUsuario').hide();

        $('input[type=radio][name=radioCrear]').change(function() {
            if (this.value == 'si') {
                $('#divCorreoUsuario').show();
                $("#correoUsuario").prop("required", true);
                $("#selectRol").prop("required", true);
            } else if (this.value == 'no') {
                $('#divCorreoUsuario').hide();
                $("#correoUsuario").prop("required", false);
                $("#selectRol").prop("required", false);
            }
        });

        $("#selectGenero").select2({
            placeholder: 'Genero'
        });
        $("#selectRol").select2({
            placeholder: 'Roles'
        });

        $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalUsuario').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Usuario agregado con Éxito!',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblEmpleados").DataTable().ajax.reload(null, false);
                        });
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

       /* $('#btnguardarUsuario').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
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
        });*/

        $("#selectRol").val('<?= $campos["rolId"]; ?>').trigger("change");
        $("#selectGenero").val('<?= $campos["sexoEmpleado"]; ?>').trigger("change");
    });
</script>
