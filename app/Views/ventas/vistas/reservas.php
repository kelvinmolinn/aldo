<h2>Reservas </h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevaReserva" class="btn btn-primary estilo-btn" onclick="modalReserva(0, 'insertar')">
            <i class="fas fa-save"></i>
            Nueva reserva
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaReserva" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Reserva</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalReserva(reservaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/form/nueva/reserva'); ?>',
                type: 'POST',
                data: { reservaId: reservaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalReserva').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalAnularReserva(reservaId, obsAnulacionReserva) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/form/anular/reserva'); ?>',
                type: 'POST',
                data: {reservaId: reservaId, obsAnulacionReserva: obsAnulacionReserva}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalAnularReserva').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
            function modalVerReserva(reservaId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/form/ver/reserva'); ?>',
                type: 'POST',
                data: {reservaId: reservaId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalAdministracionVerReserva').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
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
        tituloVentana("Nueva Reserva");

        $('#tablaReserva').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-reservas/tabla/reserva'); ?>',
                "data": function() { 
                    return {
                        x:''
                    }
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0 },   
                { "width": "25%", "targets": 1 }, 
                { "width": "20%", "targets": 2 }, 
                { "width": "30%", "targets": 3 }, 
                { "width": "20%", "targets": 4 }
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
