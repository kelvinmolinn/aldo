<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-clientes/operacion/guardar/contacto'); ?>">
    <div id="modalContactoCliente" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contacto del cliente: <?php echo $cliente; ?></h5>
                </div>
                <div class="modal-body">
                <input type="hidden" id="clienteId" name="clienteId" value="<?= $clienteId; ?>">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoContactoCliente" id="selectTipoContactoCliente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">Telefono</option>
                                    <option value="2">Correo electronico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="tipoContactoCliente" name="tipoContactoCliente" class="form-control " min ="0" required>
                                <label class="form-label" for="tipoContactoCliente">Contacto</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type= "submit" id="btnNuevoContactoProveedor" class="btn btn-primary estilo-btn" onclick="">
                                <i class="fas fa-save"></i>
                                Nuevo Contacto
                            </button>
                        </div>
                    </div>    
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaContactoCliente" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Contacto</th>
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
        function eliminarContactoCliente(id) {
    //alert("Vamos a eliminar " + id);
        Swal.fire({
            title: '¿Estás seguro que desea eliminar el Contacto?',
            text: "Se eiminara el contacto seleccionado.",
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
                        url: '<?php echo base_url('ventas/admin-clientes/eliminar/contacto/cliente'); ?>',
                        type: 'POST',
                        data: {
                            clienteContactoId: id
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Contacto eliminado con Éxito!',
                                    text: response.mensaje
                                }).then((result) => {
                                    $("#tablaContactoCliente").DataTable().ajax.reload(null, false);
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

function cargarContactoCliente() {
        // Realizar una petición AJAX para obtener los permisos relacionados con el menú seleccionado
        $.ajax({
            url: '<?php echo base_url('ventas/admin-clientes/obtener/contacto/cliente'); ?>',
            type: "POST",
            dataType: "json",
            data: {}
        }).done(function(data){
            $(`#selectTipoContactoCliente`).empty();
            $(`#selectTipoContactoCliente`).append("<option></option>");
            for (let i = 0; i < data.length; i++){
                $(`#selectTipoContactoCliente`).append($('<option>', {
                    value: data[i]['valor'],
                    text: data[i]['texto']
                }));
            }
        });
    }

    $(document).ready(function() {
        $("#selectTipoContactoCliente").select2({
            placeholder: "Tipo contacto",
            dropdownParent: $('#modalContactoCliente')
        });
        cargarContactoCliente();
        $("#selectTipoContactoCliente").change(function(e) {
            if($(this).val() == "1") {
                $('#tipoContactoCliente').inputmask('9999-9999');
            } else if($(this).val() == "2") {
                $('#tipoContactoCliente').inputmask('email');
            } else {

            }
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
                            $("#tablaContactoCliente").DataTable().ajax.reload(null, false);
                            $("#tipoContactoCliente").val('');
                            $("#selectTipoContactoCliente").val(null).trigger("change");
                            // Actualizar tabla de contactos
                            // Limpiar inputs con .val(null) o .val('')
                            
                        });
                        console.log("Último ID insertado:", response.clienteId);
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


        $('#tablaContactoCliente').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-clientes/tabla/contacto/cliente'); ?>',
                "data": {
                    clienteId:<?php echo $clienteId;?>

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
