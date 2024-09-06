<h2>Facturación </h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevaReserva" class="btn btn-secondary estilo-btn d-inline" onclick="modalNotaCredito()">
            <i class="fas fa-file-alt"></i>
            Nota de crédito
        </button>
        <?php
             if($activarContingencia['valorParametrizacion'] == 1){
        ?>
        <form id="frmActivarContingencia" method="post" action="<?php echo base_url('ventas/admin-facturacion/activar/contingencia'); ?>" class="d-inline">
            <button type= "submit" id="btnActivarContingencia" class="btn btn-warning estilo-btn" onclick="">
                <i class="fas fa-wrench"></i> 
                Contingencia DTE
            </button>
        </form>
        <?php 
             }else{
        ?>
        <button type= "button" id="btnActivarContingencia" class="btn btn-danger estilo-btn d-inline" onclick="modalFinalizarContingencia();">
            <i class="fas fa-wrench"></i> 
            Finalizar Contingencia DTE
        </button>
        <?php 
             }
        ?>
        <button type= "button" id="btnNuevaReserva" class="btn btn-primary estilo-btn d-inline" onclick="modalEmitirDTE()">
            <i class="fas fa-save"></i>
            Emitir DTE
        </button>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="CodigoDTE" name="CodigoDTE" class="form-control ">
            <label class="form-label" for="CodigoDTE">Código o número de DTE</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroClienteFacturacion" name="filtroClienteFacturacion" class="form-control ">
            <label class="form-label" for="filtroClienteFacturacion">Cliente</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="date" id="filtroFechaDTE" name="filtroFechaDTE" class="form-control">
            <label class="form-label" for="filtroFechaDTE">Fecha de DTE</label>

        </div>
    </div>
</div>
<div class="text-right mb-4">
    <button type= "button" id="btnBuscarReserva" name="btnBuscarReserva" class="btn btn-primary estilo-btn" onclick="$('#tablaDTE').DataTable().ajax.reload(null, false);">
        <i class="fas fa-search"></i>
        Buscar
    </button>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaDTE" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>DTE</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalFinalizarContingencia(facturaId){
        $.ajax({
            url: '<?php echo base_url('ventas/admin-facturacion/form/finalizar/contingencia'); ?>',
            type: 'POST',
            data: {facturaId: facturaId}, // Pasar el ID del módulo como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);
                // Mostrar la modal
                $('#modalFinalizarContingencia').modal('show');
                
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalEmitirDTE(facturaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/emitir/dte'); ?>',
                type: 'POST',
                data: {facturaId: facturaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalEmitirDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalNotaCredito(facturaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/notaCredito/dte'); ?>',
                type: 'POST',
                data: {facturaId: facturaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalNotaCredito').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalVerDTE(facturaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/ver/dte'); ?>',
                type: 'POST',
                data: {facturaId: facturaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalVerDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalVerJSON(facturaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/ver/json'); ?>',
                type: 'POST',
                data: {facturaId: facturaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalVerJSON').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalAnularDTE(facturaId, obsAnulacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/anular/dte'); ?>',
                type: 'POST',
                data: {facturaId: facturaId, obsAnulacion: obsAnulacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalAnularDTE').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function cargarJSON(facturaId) {
            $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/tabla/ver/json'); ?>', // URL correcta
                type: 'POST',
                data: { facturaId: facturaId }, // Enviar el facturaId como parámetro
                success: function(response) {
                    // Formatear y mostrar el JSON en el modal
                    $('#jsonContent').text(JSON.stringify(response, null, 4));
                    //$('#modalVerJSON').modal('show');
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error('Error al cargar el JSON:', xhr.responseText);
                }
            });
        }

    function modalImprimirDTE(facturaId) {
        $.ajax({
            url: '<?php echo base_url('ventas/admin-facturacion/form/imprimir/dte'); ?>',
            type: 'POST',
            data: { facturaId: facturaId }, // Pasar el ID de la factura como parámetro
            success: function(response) {
                // Insertar el contenido de la modal en el cuerpo de la modal
                $('#divModalContent').html(response);

                // Asumimos que el modal ya tiene un iframe con el ID `pdfFrame`
                var pdfUrl = '<?php echo base_url("ventas/admin-facturacion/pdf/generate"); ?>' + '?facturaId=' + facturaId;
                $('#pdfFrame').attr('src', pdfUrl);

                // Mostrar la modal
                $('#modalImprimirDTE').modal('show');
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function  invalidarDTE(facturaId) {
        //alert("Vamos a certificar " + id);
            Swal.fire({
                title: '¿Estás seguro que desea invalidar el DTE?',
                text: "Se invalidará el DTE.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, invalidar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar la solicitud AJAX para certificar 
                        $.ajax({
                            url: '<?php echo base_url('ventas/admin-facturacion/operacion/invalidar/dte'); ?>',
                            type: 'POST',
                            data: {
                               facturaId: facturaId
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'DTE con error!',
                                        text: response.mensaje
                                    }).then((result) => {
                                        $("#tablaDTE").DataTable().ajax.reload(null, false);
                                       // $("#tblError").DataTable().ajax.reload(null, false);
                                       // cambiarInterfaz('ventas/admin-facturacion/index', {renderVista:'No'});
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

    $(document).ready(function() {

        function EventoEnter(inputId) {
            $('#' + inputId).on('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Evita el comportamiento por defecto
                    $('#btnBuscarCompra').click(); // Simula el clic en el botón
                }
            });
        }
        EventoEnter('filtroNumReserva');
        EventoEnter('filtroFechaReserva');
        EventoEnter('filtroClienteReserva');
        

        $('input, textarea').on('focus', function() {
            $(this).addClass('active');
        });

        // Remover clase 'active' si el input está vacío al perder el foco
        $('input, textarea').on('blur', function() {
            if ($(this).val().trim() === '') {
                $(this).removeClass('active');
            }
        });
        tituloVentana("Facturación");

        $("#frmActivarContingencia").submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Está seguro que desea habilitar la contingencia de DTE?',
                text: "Se habilitará la contingencia para la certificación de DTE.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Habilitar',
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Contingencia habilitada',
                                    text: response.mensaje
                                }).then((result) => {

                                    cambiarInterfaz(`ventas/admin-facturacion/index`);

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

        $('#tablaDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-facturacion/tabla/facturacion'); ?>',
                "data": function() { 
                    return {
                        x:''
                    }
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0 },   
                { "width": "30%", "targets": 1 }, 
                { "width": "15%", "targets": 2 }, 
                { "width": "20%", "targets": 3 }, 
                { "width": "20%", "targets": 4 },
                { "width": "10%", "targets": 5 }
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
