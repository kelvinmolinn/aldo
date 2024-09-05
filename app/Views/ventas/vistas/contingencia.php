<h2>Historial de contingencias</h2>
<hr>
<div class="table-responsive">
    <table class="table table-hover" id="tablaDTE" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha/Hora inicio</th>
                <th>Fecha/Hora Fin</th>
                <th>Motivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalVerDTEContingencia(jsonData) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('ventas/admin-contingencia/form/ver/dte/contingencia'); ?>',
                type: 'POST',
                data: jsonData, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalVerDTEContingencia').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana("Historial de contingencias");

        $('#tablaDTE').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('ventas/admin-contingencia/tabla/ver/contingencia'); ?>',
                "data": function() { 
                    return {
                        x:''
                    }
                }
            },
            "columnDefs": [
                { "width": "5%", "targets": 0 },   
                { "width": "15%", "targets": 1 }, 
                { "width": "15%", "targets": 2 }, 
                { "width": "35%", "targets": 3 }, 
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
