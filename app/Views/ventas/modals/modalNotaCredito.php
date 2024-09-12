<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "DTE actualizado con éxito";
    } else {
        $mensajeAlerta = "DTE creado con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/operacion/guardar/notaCredito'); ?>">
    <div id="modalNotaCredito" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Emitir nota de crédito <?php echo ($operacion == 'editar' ? 'Editar DTE' : '');?></h5>
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
                        
                        <!-- Input para Sucursal (nombre legible) -->
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="sucursalNombre" name="sucursalNombre" class="form-control"  value="Sucursal" readonly>
                                <label class="form-label" for="sucursalNombre">Sucursal</label>
                            </div>
                            <input type="hidden" id="sucursalId" name="sucursalId"> <!-- Input oculto para el ID de la sucursal -->
                        </div>

                        <!-- Input para Tipo DTE (nombre legible) -->
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="tipoDTENombre" name="tipoDTENombre" class="form-control" value="Tipo DTE" readonly>
                                <label class="form-label" for="tipoDTENombre">Tipo DTE</label>
                            </div>
                            <input type="hidden" id="tipoDTEId" name="tipoDTEId"> <!-- Input oculto para el ID del tipo DTE -->
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Input para Cliente (nombre legible) -->
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="clienteNombre" name="clienteNombre" class="form-control" value="Cliente" readonly>
                                <label class="form-label" for="clienteNombre">Cliente</label>
                            </div>
                            <input type="hidden" id="clienteId" name="clienteId"> <!-- Input oculto para el ID del cliente -->
                        </div>

                        <!-- Input para Vendedor (nombre legible) -->
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="vendedorNombre" name="vendedorNombre" class="form-control" value="Vendedor" readonly>
                                <label class="form-label" for="vendedorNombre">Vendedor</label>
                            </div>
                            <input type="hidden" id="empleadoIdVendedor" name="empleadoIdVendedor"> <!-- Input oculto para el ID del vendedor -->
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
                    // Prellenar los campos con la información del crédito fiscal (nombres y IDs)
                    $('#sucursalNombre').val(response.sucursal);       // Nombre de la sucursal
                    $('#sucursalId').val(response.sucursalId);         // ID de la sucursal

                    $('#tipoDTENombre').val(response.tipoDTE);         // Nombre del Tipo DTE
                    $('#tipoDTEId').val(response.tipoDTEId);           // ID del Tipo DTE

                    $('#clienteNombre').val(response.cliente);         // Nombre del cliente
                    $('#clienteId').val(response.clienteId);           // ID del cliente

                    $('#vendedorNombre').val(response.vendedor);       // Nombre del vendedor
                    $('#empleadoIdVendedor').val(response.empleadoIdVendedor);  // ID del vendedor
                },
                error: function() {
                    console.error('Error al obtener la información del crédito fiscal.');
                }
            });
        } else {
            // Limpiar los campos si no se selecciona ningún crédito fiscal
            $('#sucursalNombre, #sucursalId, #tipoDTENombre, #tipoDTEId, #clienteNombre, #clienteId, #vendedorNombre, #empleadoIdVendedor').val('');
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


