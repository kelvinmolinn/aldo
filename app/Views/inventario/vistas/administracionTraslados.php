<h2>Traslados de productos</h2>
<hr>
    <div class="row mb-4">
        <div class="col-md-12 text-right">
            <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalTraslado(0, 'insertar');">
                <i class="fas fa-save nav-icon"></i>
                Nuevo traslado
            </button>
        </div>
    </div>
<div class= "table-responsive" >
    <table id="tblTraslado" name = "tblTraslado" class="table table-hover" style="width: 100%;" >
        <thead>
            <tr>
                <th>#</th>
                <th>Sucursal</th>
                <th>Fecha/Hora</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>

    //pendiente
            function modalTraslado(productoId, operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-traslados/form/traslados'); ?>',
                type: 'POST',
                data: { productoId: productoId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalTraslados').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

            //pendiente
        function modalAdministracionVerTraslado(trasladosId) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('inventario/admin-traslados/form4/verTraslado'); ?>',
                type: 'POST',
                data: { trasladosId: trasladosId,}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalAdministracionVerTraslado').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }


     $(document).ready(function() {
            tituloVentana("Traslados de productos");
            $('#tblTraslado').DataTable({
                "ajax": {
                    "method": "POST",
                    "url": '<?php echo base_url('inventario/admin-traslados/tabla/traslados'); ?>',
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