<h2>Facturación </h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevaReserva" class="btn btn-primary estilo-btn" onclick="modalEmitirDTE()">
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
    function modalImprimirDTE() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-facturacion/form/imprimir/dte'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalImprimirDTE').modal('show');
                    
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
        tituloVentana("Facturación");

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
                { "width": "20%", "targets": 2 }, 
                { "width": "25%", "targets": 3 }, 
                { "width": "10%", "targets": 4 },
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
