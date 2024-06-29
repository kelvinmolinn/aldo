<h2>Reservas </h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevaReserva" class="btn btn-primary estilo-btn" onclick="modalReserva()">
            <i class="fas fa-save"></i>
            Nueva reserva
        </button>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroNumReserva" name="filtroNumReserva" class="form-control ">
            <label class="form-label" for="filtroNumReserva">Numero de reserva</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="date" id="filtroFechaReserva" name="filtroFechaReserva" class="form-control">
            <label class="form-label" for="filtroFechaReserva">Fecha de reserva</label>

        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroClienteReserva" name="filtroClienteReserva" class="form-control ">
            <label class="form-label" for="filtroClienteReserva">Cliente</label>
        </div>
    </div>
</div>
<div class="text-right mb-4">
    <button type= "button" id="btnBuscarReserva" name="btnBuscarReserva" class="btn btn-primary estilo-btn" onclick="$('#tablaReserva').DataTable().ajax.reload(null, false);">
        <i class="fas fa-search"></i>
        Buscar
    </button>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaReserva" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Reserva</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Totales</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalReserva() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/form/nueva/reserva'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
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

    function modalAnularReserva() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-reservas/form/anular/reserva'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
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
                { "width": "15%", "targets": 1 }, 
                { "width": "20%", "targets": 2 }, 
                { "width": "20%", "targets": 3 }, 
                { "width": "20%", "targets": 4 },
                { "width": "20%", "targets": 5 } 
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
