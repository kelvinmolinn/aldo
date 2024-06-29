<h2>Permisos del rol: <?php echo  $rol;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarRol" class="btn btn-secondary estilo-btn">
            <i class="fas fa-chevron-left"> </i>
            Volver a Roles
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnNuevoPermiso" class="btn btn-primary estilo-btn">
            <i class="fas fa-plus-circle"></i>
            Nuevo permiso
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tblPermisosMenus" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Menú</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalPermisosRolMenu(menuId) {
        $.ajax({
            url: '<?php echo base_url('conf-general/admin-roles/form/permisos/rol/menu'); ?>',
            type: 'POST',
            data: {menuId: menuId},
            success: function(response) {
                
                $('#divModalContent').html(response);
                
                $('#modalPermisosRolMenu').modal('show');
            },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana('Roles - Permisos');
        $('#btnRegresarRol').on('click', function() {
            cambiarInterfaz('conf-general/admin-roles/index', {renderVista: 'No'});
        });
        $('#btnNuevoPermiso').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('conf-general/admin-roles/form/nuevo/permiso/rol'); ?>',
                type: 'POST',
                data: {
                    rolId: '<?= $rolId; ?>',
                    rol: '<?= $rol; ?>'
                },
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalNuevoPermisos').modal('show');
                   
                    // Mostrar la modal sin permitir cierre al hacer clic fuera o al presionar "Esc"
                    $('#modalNuevoPermisos').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });
        $('#tblPermisosMenus').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-roles/tabla/permisos/rol'); ?>',
                "data": {
                    rolId: '<?= $rolId; ?>',
                    rol: '<?= $rol; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "40%"}, 
                { "width": "35%"}
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