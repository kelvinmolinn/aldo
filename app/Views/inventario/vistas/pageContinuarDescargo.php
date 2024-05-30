
<input type="hidden" id="descargosId" name="descargosId" value="<?= $descargosId; ?>">

<h2>Continuar descargo N°: <?php echo $descargosId;?> - <?php echo $dataSucursal['sucursal'];?></h2>
<hr>
<div class="row mb-4">
<div class="col-md-6">
        <button type= "button" id="btnRegresarDescargo" class="btn btn-secondary estilo-btn">
        <i class="fas fa-backspace "></i>
            Volver 
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModalProducto" class="btn btn-primary" onclick="modalAdministracionNuevaSalida(0, 'insertar');">
        <i class="fas fa-save nav-icon "></i>
            Agregar producto 
        </button>
    </div>
</div>
<div class= "table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table id="tblContinuarSalida" name = "tblContinuarSalida" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Motivo/Justificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
    <br>
    <div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "submit" id="btnFinalizar" class="btn btn-primary" onclick="finalizarDescargo();">
        <i class="fas fa-save nav-icon "></i>
            Finalizar descargo 
        </button>
    </div>
</div>
<script>
    function finalizarDescargo(descargosId) {
                $.ajax({
                    url: '<?php echo base_url('inventario/admin-salida/operacion/finalizar/finalizar'); ?>',
                    type: 'POST',
                    data: {
                        descargosId: <?php echo $descargosId ;?>
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Salida/descargo eliminada con Éxito!',
                                text: response.mensaje
                            }).then((result) => {
                                $("#tblContinuarSalida").DataTable().ajax.reload(null, false);
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
    function eliminarSalida(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar la salida de producto?',
                text: "Se eiminara la salida de producto seleccionada.",
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
                            url: '<?php echo base_url('inventario/admin-salida/operacion/eliminar/salida'); ?>',
                            type: 'POST',
                            data: {
                                descargoDetalleId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Salida/descargo eliminada con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblContinuarSalida").DataTable().ajax.reload(null, false);
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
        //pendiente
        function modalAdministracionNuevaSalida(descargoDetalleId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-salida/form2/Nuevasalida'); ?>',
                type: 'POST',
                data: { descargoDetalleId: descargoDetalleId, operacion: operacion, descargosId: <?= $descargosId; ?>}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalNuevaSalida').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana("Descargos/Continuar Descargo");
        $('#btnRegresarDescargo').on('click', function() {
            cambiarInterfaz('inventario/admin-salida/index', {renderVista: 'No'});
        });
        $('#tblContinuarSalida').DataTable({
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-salida/tabla/ContinuarSalida'); ?>',
                    "data": {
                        descargosId: <?php echo $descargosId ;?>
                    }
                },
                "columnDefs": [
                    { "width": "10%"}, 
                    { "width": "25%"}, 
                    { "width": "25%"},
                    { "width": "25%"},  
                    { "width": "15%"}  
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