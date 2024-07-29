<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-facturacion/concepto/dte'); ?>">
    <div id="modalConceptoDTE" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Concepto del producto 
                       
                </div>
                <div class="modal-body">
                <input type="hidden" id="facturaDetalleId" name="facturaDetalleId" Value="<?php echo $campos['facturaDetalleId']?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="conceptoProducto" name="conceptoProducto" class="form-control" value="">
                                <label class="form-label" for="conceptoProducto">Concepto del producto</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarAnulacion" class="btn btn-primary">
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

                $("#frmModal").submit(function(event) {
            event.preventDefault();
            Swal.fire({
            title: '¿Estás seguro que desea agregar concepto al DTE?',
            text: "Se agregará el concepto al DTE seleccionado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Anular',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'), 
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            // Insert exitoso, ocultar modal y mostrar mensaje
                            $('#modalConceptoDTE').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Concepto agregado con éxito',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tablaContinuarDTE").DataTable().ajax.reload(null, false);

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
            }
        });

        });
    });
</script>
