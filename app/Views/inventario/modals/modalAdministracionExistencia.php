<?php
    if ($operacion == "editar") {
        $mensajeAlerta = "Existencia actualizado con éxito";
    } else {
        $mensajeAlerta = "Existencia creada con éxito";
    }
?>

<form id="frmModal"  method="POST">
    <div id="modalExistencia" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ($operacion == 'editar' ? 'Editar Producto' : 'Nueva existencia inicial'); ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="productoExistenciaId" name="productoExistenciaId" value="<?= $campos['productoExistenciaId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-select-control">
                                <select name="sucursalId" id="sucursalId" class="form-control " style="width: 100%;">
                                <option></option>
                                    <?php foreach ($sucursales as $sucursal) : ?>
                                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-select-control">
                                <select name="productoId" id="productoId" class="form-control " style="width: 100%;">
                                <option></option>
                                    <?php foreach ($producto as $producto) : ?>
                                        <option value="<?php echo $producto['productoId']; ?>"><?php echo $producto['producto']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div> <br>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnGuardarExistencia" class="btn btn-primary">
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
            placeholder: 'Sucursal'
        });

     $("#productoId").select2({
            placeholder: 'Producto'
        });

    $('#btnGuardarExistencia').on('click', function() {
        // Realizar una petición AJAX para obtener el contenido de la modal
        $.ajax({
            url: '<?php echo base_url('inventario/admin-producto/operacion/guardar/producto'); ?>',
            type: 'POST',
            data: $("#frmModal").serialize(),
            success: function(response) {
                console.log(response);
                if (response.success) {
                    // Insert exitoso, ocultar modal y mostrar mensaje con Sweet Alert
                    $('#modalExistencia').modal('hide');
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
                        text: 'El Codigo de producto ya está registrado en la base de datos o Hay algun dato incompleto, Verifique las validaciones!'
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
