<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-retaceo/anular/retaceo'); ?>">
    <div id="modalAnularRetaceo" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal Anular</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="retaceoId" name="retaceoId" Value="<?php echo $campos['retaceoId']?>">
                    <div class="row mb-2">
                        <div class="col-md-4">
                                <label>Numero de retaceo: </label><?php echo $campos['numRetaceo']?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label> total flete: </label> $ <?php echo $campos['totalFlete']?>
                        </div>
                        <div class="col-md-6">
                            <label> total gastos: </label> $ <?php echo $campos['totalGastos']?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label> Costo total:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <textarea name="observacionAnulacion" id="observacionAnulacion" class="form-control" style="width: 100%;" required></textarea>
                                <label class="form-label" for="observacionAnulacion">Observación</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnAnularRetaceo" class="btn btn-primary">
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
            title: '¿Estás seguro que desea anular el retaceo?',
            text: "Se anulara el retaceo seleccionado.",
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
                            $('#modalAnularRetaceo').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'retaceo anulado con éxito',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tablaRetaceo").DataTable().ajax.reload(null, false);

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