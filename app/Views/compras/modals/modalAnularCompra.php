<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-compras/anular/compra'); ?>">
    <div id="modalAnularCompra" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal Anular Compra</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="compraId" name="compraId" Value="<?php echo $campos['compraId']?>">
                    <div class="row mb-2">
                        <div class="col-md-4">
                                <label>Numero de la compra: </label> <?php echo $campos['numFactura']?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <textarea name="observacionCompra" id="observacionCompra" class="form-control" style="width: 100%;" required></textarea>
                                <label class="form-label" for="observacionCompra">Observación</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnAnularCompra" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Anular
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
    $(document).ready(function(){
        
        $("#frmModal").submit(function(event) {
            event.preventDefault();
            Swal.fire({
            title: '¿Estás seguro que desea anular la compra?',
            text: "Se anulara la compra seleccionada.",
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
                            $('#modalAnularCompra').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'retaceo anulado con éxito',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tablaCompras").DataTable().ajax.reload(null, false);

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
    })
</script>