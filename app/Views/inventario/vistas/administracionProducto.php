<h2>Inventario de productos</h2>
<hr>

<div class="row mb-4">
    <div class="col-md-6 text-left">
        <button type="button" id="btnAbrirModal2" class="btn btn-secondary" onclick="modalAlertaExistencia(0);">
            Existencia mínima (<span id="countMinima">0</span>)
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModal2" class="btn btn-primary" onclick="modalExistencia(0, 'insertar');">
            <i class="fas fa-save"></i>
            Existencia Inicial
        </button>
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalProducto(0, 'insertar');">
            <i class="fas fa-save"></i>
            Nuevo producto
        </button>
    </div>
</div>
<div class= "table-responsive">
    <table id="tblProducto" name = "tblProducto" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Características</th>
                <th>Precio de venta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>

       function eliminarProducto(id) {
        //alert("Vamos a eliminar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea eliminar el producto?',
                text: "Se eiminara el producto seleccionado.",
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
                            url: '<?php echo base_url('inventario/admin-producto/operacion/eliminar/producto'); ?>',
                            type: 'POST',
                            data: {
                                productoId: id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Producto eliminado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblProducto").DataTable().ajax.reload(null, false);
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
        
    function cambiarEstadoProducto(datos) {
            Swal.fire({
                title: datos.mensaje,
                text: datos.mensaje2,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar estado',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para eliminar el usuario de la sucursal
                        $.ajax({
                            url: '<?php echo base_url('inventario/admin-producto/operacion/estado/usuario'); ?>',
                            type: 'POST',
                            data: {
                                productoId: datos.productoId,
                                estadoProducto : datos.estadoProducto
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Se cambió el estado con Éxito!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tblProducto").DataTable().ajax.reload(null, false);
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
        function modalProducto(productoId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-producto/form/producto'); ?>',
                type: 'POST',
                data: { productoId: productoId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalProducto').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalAlertaExistencia(productoId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-producto/form5/AlertaExistencia'); ?>',
                type: 'POST',
                data: { productoId: productoId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    $('#divModalContent').html(response);
                    $('#modalAlertaExistencia').modal('show');

                    // Actualizamos el contador en el botón
                    $('#btnAbrirModal2').find('acountMinima').text(response.countMinima);
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalExistencia(productoId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-producto/form3/existencia'); ?>',
                type: 'POST',
                data: { productoId: productoId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalExistencia').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalExistenciaProducto(productoId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-producto/form4/existenciaProducto'); ?>',
                type: 'POST',
                data: { productoId: productoId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalExistenciaProducto').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalPrecios(productoId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-producto/form2/precio'); ?>',
                type: 'POST',
                data: { productoId: productoId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalPrecios').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
        $(document).ready(function() {
            tituloVentana("Existencias Productos");
            $('#tblProducto').DataTable({
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-producto/tabla/producto'); ?>',
                    "data": {
                        x: ''
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