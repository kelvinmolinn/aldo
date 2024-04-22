<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Configuración de roles</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" class="btn btn-primary ttip" onclick="modalRoles(0,'insertar');">
            <i class="fas fa-user-plus"></i>
            Nuevo rol
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaRoles" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalRoles(rolId,operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('conf-general/admin-roles/form/nuevo/rol'); ?>',
                type: 'POST',
                data: {rolId: rolId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalRol').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        $('#tablaRoles').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-roles/tabla/roles'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 } 
            ],
            "language": {
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>
<?= $this->endSection(); ?>