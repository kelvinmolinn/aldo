<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-reservas/operacion/guardar/pagoReserva'); ?>">
    <div id="modalPagoReservas" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pago de la reserva - Número de reserva: <?php echo $reservaId; ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reservaId" name="reservaId" value="<?= $reservaId; ?>">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="formaPagoMHId" id="formaPagoMHId" style="width: 100%;" required>
                                    <option></option>
                                    <?php foreach ($formaPago as $pago) : ?>
                                        <option value="<?php echo $pago['formaPagoMHId']; ?>"><?php echo $pago['formaPago']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="numComprobantePago" name="numComprobantePago" class="form-control active" required>
                                <label class="form-label" for="numComprobantePago">Número de comprobante</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="number" id="montoPago" name="montoPago" class="form-control active number-input" min="0.01" step="0.01" required>
                                <label class="form-label" for="montoPago">Monto</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="date" id="fechaReservaPago" name="fechaReservaPago" class="form-control active" required>
                                <label class="form-label" for="fechaReservaPago">Fecha de pago</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="comentarioPago" name="comentarioPago" class="form-control active" required>
                                <label class="form-label" for="comentarioPago">Comentario</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type= "submit" id="btnNuevoContactoProveedor" class="btn btn-primary estilo-btn" onclick="">
                                <i class="fas fa-save"></i>
                                Nuevo pago
                            </button>
                        </div>
                    </div>      
                    <div class="table-responsive">
                        <table class="table table-hover" id="tblPagoReserva" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pago</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>                             
                </div>
                
                <div class="modal-footer">
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

function eliminarReservaPago(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el abono al pago de la reserva?',
                text: "Se eiminara el pago de la reserva seleccionada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                        $.ajax({
                            url: '<?php echo base_url('ventas/admin-reservas/operacion/eliminar/pago'); ?>',
                            type: 'POST',
                            data: {
                                reservaPagoId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pago Eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblPagoReserva").DataTable().ajax.reload(null, false);
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
    }
        // Evitar la entrada de 'e', 'E', '+', y '-' en los campos de número
        document.querySelectorAll('.number-input').forEach(function(input) {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'e' || event.key === 'E' || event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
        });
    });

    $(document).ready(function() {
        $("#formaPagoMHId").select2({
            placeholder: "Forma de pago"
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Pago agregado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblPagoReserva").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.reservaPagoId);
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
        });

        $('#tblPagoReserva').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-reservas/tabla/pago/ventas'); ?>',
                "data": {
                    reservaId:<?php echo $reservaId;?>
                }
            },
            "columnDefs": [
                { "width": "20%", "targets": 0 }, 
                { "width": "36%", "targets": 1 }, 
                { "width": "19%", "targets": 2 },
                { "width": "10%", "targets": 3 },
                { "width": "15%", "targets": 4 }
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
            "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>
