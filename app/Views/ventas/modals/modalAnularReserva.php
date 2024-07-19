<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-reservas/anular/reserva'); ?>">
    <div id="modalAnularReserva" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Anular reserva 
                       
                </div>
                <div class="modal-body">
                <input type="hidden" id="reservaId" name="reservaId" Value="<?php echo $campos['reservaId']?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="obsAnulacionReserva" name="obsAnulacionReserva" class="form-control" value="">
                                <label class="form-label" for="obsAnulacionReserva">Motivo de la anulación</label>
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
            title: '¿Estás seguro que desea anular la reserva?',
            text: "Se anulara la reserva seleccionada.",
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
                            $('#modalAnularReserva').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'retaceo anulado con éxito',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tablaReserva").DataTable().ajax.reload(null, false);

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
