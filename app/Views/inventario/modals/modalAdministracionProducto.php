<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Producto actualizado con éxito";
    } else {
        $mensajeAlerta = "Producto creado con éxito";
    }
?>

<form id="frmModal" action="<?= base_url('inventario/admin-producto/operacion/guardar/producto')?>" method="POST">
    <div id="modalProducto" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Producto' : 'Nuevo Producto'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productoId" name="productoId" value="<?= $campos['productoId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="codigoProducto" name="codigoProducto" class="form-control" value="<?= $campos['codigoProducto']; ?>" required>
                                <label class="form-label" for="codigoProducto">Código de producto</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="producto" name="producto" class="form-control" value="<?= $campos['producto']; ?>" required>
                                <label class="form-label" for="producto">Producto</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <textarea name="descripcionProducto" id="descripcionProducto" class="form-control" style="width: 100%;" required><?= $campos['descripcionProducto']; ?></textarea>
                                <label class="form-label" for="descripcionProducto">Descripción del producto</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaInicioInventario" name="fechaInicioInventario" class="form-control" required>
                                <label class="form-label" for="fechaInicioInventario">Fecha inicio</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="productoTipoId" id="productoTipoId" class="form-control" style="width: 100%;">
                                    <option></option>
                                    <?php foreach ($tipo as $productoTipo) : ?>
                                        <option value="<?= $productoTipo['productoTipoId']; ?>"><?= $productoTipo['productoTipo']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="productoPlataformaId" id="productoPlataformaId" class="form-control" style="width: 100%;">
                                    <option></option>
                                    <?php foreach ($plataforma as $productoPlataforma) : ?>
                                        <option value="<?= $productoPlataforma['productoPlataformaId']; ?>"><?= $productoPlataforma['productoPlataforma']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="unidadMedidaId" id="unidadMedidaId" class="form-control" style="width: 100%;">
                                    <option></option>
                                    <?php foreach ($unidad as $unidadMedida) : ?>
                                        <option value="<?= $unidadMedida['unidadMedidaId']; ?>"><?= $unidadMedida['unidadMedida']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="existenciaMinima" name="existenciaMinima" class="form-control" value="<?= $campos['existenciaMinima']; ?>" required>
                                <label class="form-label" for="existenciaMinima">Existencia mínima</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="flgProductoVenta" id="flgProductoVenta" class="form-control" style="width: 100%;">
                                    <option value="1">Producto para venta</option>
                                    <option value="2">Producto para uso interno</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" id="btnguardarProducto" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#productoTipoId").select2({
        placeholder: 'Tipo de producto'
    });
    $("#productoPlataformaId").select2({
        placeholder: 'Tipo de plataforma'
    });
    $("#unidadMedidaId").select2({
        placeholder: 'Unidad de medida'
    });
    $("#flgProductoVenta").select2({
        placeholder: 'Uso de producto'
    });

    $('#btnguardarProducto').on('click', function() {
        // Realizar una petición AJAX para obtener el contenido de la modal
        $.ajax({
            url: '<?= base_url('inventario/admin-producto/operacion/guardar/producto'); ?>',
            type: 'POST',
            data: $("#frmModal").serialize(),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                    $('#modalProducto').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '<?= $mensajeAlerta; ?>',
                        text: response.mensaje
                    }).then((result) => {
                        $("#tblProducto").DataTable().ajax.reload(null, false);
                    });
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
                        text: 'El Código de producto ya está registrado en la base de datos o Hay algún dato incompleto, ¡Verifique las validaciones!'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    });

    $("#productoTipoId").val('<?= $campos["productoTipoId"]; ?>').trigger("change");
    $("#productoPlataformaId").val('<?= $campos["productoPlataformaId"]; ?>').trigger("change");
    $("#unidadMedidaId").val('<?= $campos["unidadMedidaId"]; ?>').trigger("change");
});
</script>
