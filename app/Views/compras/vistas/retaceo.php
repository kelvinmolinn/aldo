<h2>Retaceo</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevoRetaceo" class="btn btn-primary estilo-btn" onclick="modalRetaceo()">
            <i class="fas fa-save"></i>
            Nuevo retaceo
        </button>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroNumRetaceo" name="filtroNumRetaceo" class="form-control ">
            <label class="form-label" for="filtroNumRetaceo">Numero de retaceo</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="text" id="filtroProveedorRetaceo" name="filtroProveedorRetaceo" class="form-control ">
            <label class="form-label" for="filtroProveedorRetaceo">Proveedor</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-outline">
            <input type="date" id="filtroFechaRetaceo" name="filtroFechaRetaceo" class="form-control">
            <label class="form-label" for="filtroFechaRetaceo">Fecha del Retaceo</label>

        </div>
    </div>
</div>
<div class="text-right mb-4">
    <button type= "button" id="btnBuscarRetaceo" name="btnBuscarRetaceo" class="btn btn-primary estilo-btn" onclick="$('#tablaRetaceo').DataTable().ajax.reload(null, false);">
        <i class="fas fa-search"></i>
        Buscar
    </button>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaRetaceo" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Retaceo</th>
                <th>Costos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>
<script>
    function modalRetaceo() {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('compras/admin-retaceo/form/nuevo/retaceo'); ?>',
                type: 'POST',
                data: {}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalNuevoRetaceo').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    function modalAnularRetaceo(retaceoId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('compras/admin-retaceo/form/anular/retaceo'); ?>',
                type: 'POST',
                data: {retaceoId: retaceoId}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalAnularRetaceo').modal('show');
                    
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
                $('#btnBuscarRetaceo').click(); // Simula el clic en el botón
            }
        });
    }
    EventoEnter('filtroNumRetaceo');
    EventoEnter('filtroProveedorRetaceo');
    EventoEnter('filtroFechaRetaceo');
    

    $('input, textarea').on('focus', function() {
        $(this).addClass('active');
    });

    // Remover clase 'active' si el input está vacío al perder el foco
    $('input, textarea').on('blur', function() {
        if ($(this).val().trim() === '') {
            $(this).removeClass('active');
        }
    });
        tituloVentana("Nuevo Retaceo");

        $('#tablaRetaceo').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-retaceo/tabla/retaceo'); ?>',
                "data": function() { 
                    return {
                        x:'' 
                    }
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "30%", "targets": 2 }, 
                { "width": "20%", "targets": 3 }
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
