<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Salida actualizado con éxito";
    } else {
        $mensajeAlerta = "Salida creada con éxito";
    }
?>
<form id="frmModal" action="<?= base_url('inventario/admin-traslados/operacion/guardar/traslados') ?>" method="POST">
    <div id="modalTraslados" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Traslado' : 'Nueva nuevo traslado'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="trasladosId" name="trasladosId" value="<?= $campos['trasladosId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="sucursalIdSalida" id="sucursalIdSalida" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($sucursales as $sucursal) : ?>
                                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="sucursalIdEntrada" id="sucursalIdEntrada" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($sucursales as $sucursal) : ?>
                                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="empleadoIdSalida" id="empleadoIdSalida" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($empleados as $empleado) : ?>
                                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="empleadoIdEntrada" id="empleadoIdEntrada" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($empleados as $empleado) : ?>
                                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="obsSolicitud" name="obsSolicitud" class="form-control"  value="<?= $campos['obsSolicitud']; ?>" required>
                                <label class="form-label" for="obsSolicitud">Observación de la solicitud del traslado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="date" id="fechaTraslado" name="fechaTraslado" class="form-control"  value="<?= $campos['fechaTraslado']; ?>" required>
                                <label class="form-label" for="fechaTraslado">Fecha de traslado</label>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnGuardarExistencia" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Iniciar
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
    // Inicializar Select2
    $("#sucursalIdSalida").select2({ placeholder: 'Sucursal Envía' });
    $("#sucursalIdEntrada").select2({ placeholder: 'Sucursal Recibe' });
    $("#empleadoIdSalida").select2({ placeholder: 'empleado Envía' });
    $("#empleadoIdEntrada").select2({ placeholder: 'empleado Recibe' });
   
   
    // Evitar la entrada de 'e', 'E', '+', y '-' en los campos de número
    document.querySelectorAll('.number-input').forEach(function(input) {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'e' || event.key === 'E' || event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
        });
    });

    $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalTraslados').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblTraslado").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.trasladosId);

                } else {
                    // Insert fallido, mostrar mensaje de error con Sweet Alert
                    let errorMessage = '<ul>';
                    $.each(response.errors, function(key, value) {
                        errorMessage += '<li>' + value + '</li>';
                    });
                    errorMessage += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Hay algun dato incompleto o erroneo, Verifique las validaciones!'
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
