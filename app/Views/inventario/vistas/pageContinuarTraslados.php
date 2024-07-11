<form id="frmActualizarTraslado" method="post" action="<?php echo base_url('inventario/admin-traslados/operacion/actualizar/traslado'); ?>">
<input type="hidden" id="trasladosId" name="trasladosId" value="<?= $trasladosId; ?>">
<h2>Continuar traslado N°: <?php echo $trasladosId;?> </h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarTraslado" class="btn btn-secondary estilo-btn">
        <i class="fas fa-backspace "></i>
            Volver 
        </button>
    </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="sucursalIdSalida" id="sucursalIdSalida" class="form-control" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($sucursales as $sucursal) : ?>
                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="sucursalIdEntrada" id="sucursalIdEntrada" class="form-control" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($sucursales as $sucursal) : ?>
                        <option value="<?php echo $sucursal['sucursalId']; ?>"><?php echo $sucursal['sucursal']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-outline">
                <input type="text" id="obsSolicitud" name="obsSolicitud" class="form-control active"  value="<?= $camposEncabezado['obsSolicitud']; ?>" required>
                <label class="form-label" for="obsSolicitud">Observación de la solicitud del traslado</label>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="empleadoIdSalida" id="empleadoIdSalida" class="form-control active" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-select-control">
                <select name="empleadoIdEntrada" id="empleadoIdEntrada" class="form-control active" style="width: 100%;" required>
                    <option></option>
                    <?php foreach ($empleados as $empleado) : ?>
                        <option value="<?php echo $empleado['empleadoId']; ?>"><?php echo $empleado['primerNombre']; ?> <?php echo $empleado['primerApellido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-outline">
                <input type="date" id="fechaTraslado" name="fechaTraslado" class="form-control active"  value="<?= $camposEncabezado['fechaTraslado']; ?>" required>
                <label class="form-label" for="fechaTraslado">Fecha de traslado</label>
            </div>
        </div>
    </div>
    <br>
    <div class="text-right">
            <button type="submit" id="btnguardarTraslado" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i>
                Actualizar compra
            </button>
        </div>
</form>
    <hr>
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModalProducto" class="btn btn-primary" onclick="modalAdministracionNuevoTraslado(0, 'insertar');">
        <i class="fas fa-save nav-icon "></i>
            Agregar producto
        </button>
    </div>
<div class= "table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table id="tblContinuarTraslados" name = "tblContinuarTraslados" class="table table-hover" style="width: 100%;">
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
<hr>
</div>

<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "submit" id="btnFinalizar" class="btn btn-primary" onclick="finalizarTraslado();">
        <i class="fas fa-save nav-icon "></i>
            Finalizar traslado
        </button>
    </div>
</div>

<script>
        function finalizarTraslado(trasladosId) {
        Swal.fire({
            title: '¿Estás seguro que desea Finlizar el traslado?',
            text: "Se Finalizará el traslado seleccionado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Finalizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('inventario/admin-traslados/operacion/finalizar/traslado'); ?>',
                    type: 'POST',
                    data: {
                        trasladosId: <?php echo $trasladosId ;?>
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Traslado Finalizado con Éxito!',
                                text: response.mensaje
                            }).then((result) => {
                               // $("#tblContinuarSalida").DataTable().ajax.reload(null, false);
                               cambiarInterfaz('inventario/admin-traslados/index', {renderVista:'No'});
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
      function eliminarTraslado(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el traslado de producto?',
                text: "Se eiminara el producto del traslado seleccionado.",
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
                            url: '<?php echo base_url('inventario/admin-traslados/operacion/eliminar/traslado'); ?>',
                            type: 'POST',
                            data: {
                                trasladoDetalleId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Traslado Eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblContinuarTraslados").DataTable().ajax.reload(null, false);
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
            function modalAdministracionNuevoTraslado(trasladoDetalleId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-traslados/form2/Nuevasalida'); ?>',
                type: 'POST',
                data: { trasladoDetalleId: trasladoDetalleId, operacion: operacion, trasladosId: <?= $trasladosId; ?>}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalNuevoTraslado').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana("Traslados");
        $('#btnRegresarTraslado').on('click', function() {
            cambiarInterfaz('inventario/admin-traslados/index', {renderVista: 'No'});
        });


            // Inicializar Select2
        $("#sucursalIdSalida").select2({ placeholder: 'Sucursal Envía' });
        $("#sucursalIdEntrada").select2({ placeholder: 'Sucursal Recibe' });
        $("#empleadoIdSalida").select2({ placeholder: 'empleado Envía' });
        $("#empleadoIdEntrada").select2({ placeholder: 'empleado Recibe' });

        $("#frmActualizarTraslado").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Traslado actualizado con éxito',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tblTraslado").DataTable().ajax.reload(null, false);
                            $("#tblContinuarTraslados ").DataTable().ajax.reload(null, false);
                            // Actualizar tabla de contactos
                            // Limpiar inputs con .val(null) o .val('')
                            
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
        });

        $("#frmContinuarTraslado").submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro que desea finalizar el traslado?',
                text: "Se finalizara el traslado seleccionado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Traslado finalizado con éxito',
                                    text: response.mensaje
                                }).then((result) => {

                                    cambiarInterfaz(`inventario/admin-traslados/index`);
                                    // Actualizar tabla de contactos
                                    // Limpiar inputs con .val(null) o .val('')
                                    
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

        $('#tblContinuarTraslados').DataTable({
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-traslados/tabla/ContinuarTraslado'); ?>',
                    "data": {
                        trasladosId: <?php echo $trasladosId ;?>
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
            
            $("#sucursalIdSalida").val(<?= $camposEncabezado["sucursalIdSalida"]; ?>).trigger('change');    
            $("#sucursalIdEntrada").val(<?= $camposEncabezado["sucursalIdEntrada"]; ?>).trigger('change');
            $("#empleadoIdSalida").val(<?= $camposEncabezado["empleadoIdSalida"]; ?>).trigger('change');    
            $("#empleadoIdEntrada").val(<?= $camposEncabezado["empleadoIdEntrada"]; ?>).trigger('change');
    });
</script>