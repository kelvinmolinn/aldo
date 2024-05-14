<form id="frmModal">
    <div id="modalContactoProveedor" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contacto del proveedor: <?php echo $proveedor; ?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="proveedorId" name="proveedorId" value="<?= $proveedorId; ?>">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoContacto" id="selectTipoContacto" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Local">Proveedor local</option>
                                    <option value="Internacional">Proveedor Internacional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="tipoContacto" name="tipoContacto" class="form-control " placeholder="Contacto" min ="0" required>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type= "button" id="btnNuevoContactoProveedor" class="btn btn-primary estilo-btn" onclick="">
                                <i class="fas fa-save"></i>
                                Nuevo Contacto
                            </button>
                        </div>
                    </div>    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaContactoProveedor" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Contato</th>
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
    $(document).ready(function() {
        $("#selectTipoContacto").select2({
            placeholder: "Tipo contacto"
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
                            title: 'Contato agregado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaProveedores").DataTable().ajax.reload(null, false);
                            // Actualizar tabla de contactos
                            // Limpiar inputs con .val(null) o .val('')
                            
                        });
                        console.log("Último ID insertado:", response.proveedorId);
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

        $('#tablaContactoProveedor').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-proveedores/tabla/contacto/proveedor'); ?>',
                "data": {
                    x:''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "30%", "targets": 2 }
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
