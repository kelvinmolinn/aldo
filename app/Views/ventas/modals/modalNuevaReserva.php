<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Reserva actualizada con éxito";
    } else {
        $mensajeAlerta = "Reserva creada con éxito";
    }
?>

<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-reservas/operacion/guardar/reserva'); ?>">
    <div id="modalReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Nueva Reserva 
                        <?php echo ($operacion == 'editar' ? 'Editar reserva' : 'Nueva reserva');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                        <div class="form-select-control">
                                <select name="sucursalId" id="sucursalId" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($sucursales as $sucursal) : ?>
                                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="clienteId" id="clienteId" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($clientes as $cliente) : ?>
                                        <option value="<?php echo $cliente['clienteId']; ?>"><?php echo $cliente['cliente']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaReserva" name="fechaReserva" class="form-control numero" value="<?= $campos['fechaReserva']; ?>" required>
                                <label class="form-label" for="fechaReserva">Fecha de la reserva</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="comentarioReserva" name="comentarioReserva" class="form-control" value="<?= $campos['comentarioReserva']; ?>" required>
                                <label class="form-label" for="comentarioReserva">Comentarios</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarCliente" class="btn btn-primary">
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

        $("#sucursalId").select2({
            placeholder: 'Sucursal',
            dropdownParent: $('#modalReserva')
        });

        $("#clienteId").select2({
            placeholder: 'Cliente',
            dropdownParent: $('#modalReserva')
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
                        $('#modalReserva').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaReserva").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.reservaId);

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
