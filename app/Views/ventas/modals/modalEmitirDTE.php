<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "DTE actualizado con éxito";
    } else {
        $mensajeAlerta = "DTE creado con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/guardar/dte'); ?>">
    <div id="modalEmitirDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Emitir DTE 
                        <?php echo ($operacion == 'editar' ? 'Editar DTE' : 'Nuevo DTE');?></h5>
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
                            <select name="tipoDTEId" id="tipoDTEId" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($tipoDTE as $tipoDTE) : ?>
                                        <option value="<?php echo $tipoDTE['tipoDTEId']; ?>"><?php echo $tipoDTE['tipoDocumentoDTE']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-outline">
                            <input type="date" id="fechaEmision" name="fechaEmision" class="form-control numero" value="<?php echo date('Y-m-d'); ?>" readonly required>
                            <label class="form-label" for="fechaEmision">Fecha emisión</label>
                        </div>
                    </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="clienteId" id="clienteId" class="form-control" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($clientes as $cliente) : ?>
                                        <option value="<?php echo $cliente['clienteId']; ?>"><?php echo $cliente['cliente']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="empleadoIdVendedor" id="empleadoIdVendedor" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($empleados as $empleado) : ?>
                                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                                    <?php endforeach; ?>
                                </select>
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
            dropdownParent: $('#modalEmitirDTE')
        });

        $("#tipoDTEId").select2({
            placeholder: 'Tipo DTE',
            dropdownParent: $('#modalEmitirDTE')
        });     
        $("#clienteId").select2({
            placeholder: 'Cliente',
            dropdownParent: $('#modalEmitirDTE')
        });
        $("#empleadoIdVendedor").select2({
            placeholder: 'Vendedor',
            dropdownParent: $('#modalEmitirDTE')
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
                        $('#modalEmitirDTE').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaDTE").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.facturaId);

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
