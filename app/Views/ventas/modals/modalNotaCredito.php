<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "DTE actualizado con éxito";
    } else {
        $mensajeAlerta = "DTE creado con éxito";
    }
?>

<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/guardar/dte'); ?>">
    <div id="modalNotaCredito" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Emitir DTE <?php echo ($operacion == 'editar' ? 'Editar DTE' : 'Nuevo DTE');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Select de Crédito Fiscal -->
                        <div class="col-md-4">
                            <div class="form-select-control">
                               
                                <select name="creditoFiscalId" id="creditoFiscalId" class="form-control" style="width: 100%;">
                                    <option value="">Seleccione un crédito fiscal</option>
                                    <?php foreach ($creditosFiscales as $credito) : ?>
                                        <option value="<?php echo $credito['facturaId']; ?>"><?php echo $credito['numeroControl']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Input para Sucursal (readonly) -->
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="sucursal" name="sucursal" class="form-control" value="Sucursal" readonly>
                                <label class="form-label" for="sucursal">Sucursal</label>
                            </div>
                        </div>
                        <!-- Input para Tipo DTE (readonly) -->
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="tipoDTE" name="tipoDTE" class="form-control" value="Tipo DTE" readonly>
                                <label class="form-label" for="tipoDTE">Tipo DTE</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <!-- Input para Cliente (readonly) -->
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="cliente" name="cliente" class="form-control" value="Cliente" readonly>
                                <label class="form-label" for="cliente">Cliente</label>
                            </div>
                        </div>
                        <!-- Input para Vendedor (readonly) -->
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="vendedor" name="vendedor" class="form-control" value="Vendedor" readonly>
                                <label class="form-label" for="vendedor">Vendedor</label>
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
    // Initialize Select2 for Crédito Fiscal only
    $("#creditoFiscalId").select2({
        placeholder: 'Seleccione un crédito fiscal',
        dropdownParent: $('#modalNotaCredito')
    });

    // Evento para cargar la información del crédito fiscal seleccionado
    $('#creditoFiscalId').on('change', function() {
        var creditoFiscalId = $(this).val();
        if (creditoFiscalId) {
            $.ajax({
                url: '<?php echo base_url("ventas/admin-facturacion/obtener/credito-fiscal"); ?>',
                type: 'POST',
                data: { facturaId: creditoFiscalId },
                success: function(response) {
                    // Prellenar los campos con la información del crédito fiscal
                    $('#sucursal').val(response.sucursal);       // Prellenar sucursal
                    $('#tipoDTE').val(response.tipoDTE);         // Prellenar tipo DTE
                    $('#cliente').val(response.cliente);         // Prellenar cliente
                    $('#vendedor').val(response.vendedor);       // Prellenar vendedor
                },
                error: function() {
                    console.error('Error al obtener la información del crédito fiscal.');
                }
            });
        } else {
            // Limpiar los campos si no se selecciona ningún crédito fiscal
            $('#sucursal, #tipoDTE, #cliente, #vendedor').val('');
        }
    });

    // Form submission with AJAX
    $("#frmModal").submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#modalNotaCredito').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo $mensajeAlerta; ?>',
                        text: response.mensaje
                    }).then((result) => {
                        $("#tablaDTE").DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Hay algún dato incompleto o erróneo. Verifique las validaciones.'
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
