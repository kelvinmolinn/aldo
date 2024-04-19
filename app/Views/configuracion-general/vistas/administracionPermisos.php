<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Permisos del menu: <?= $menu;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresar" class="btn btn-secondary estilo-btn">
            <i class="fas fa-angle-double-left"> </i>
            Volver a menus
        </button>
    </div>
    <div class="col-md-12 text-right">
        <button type= "button" class="btn btn-primary ttip" onclick="modalPermisos(0,'insertar');">
            <i class="fas fa-user-plus"></i>
            Nuevo permiso
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaPermisos" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Menú</th>
                <th>Permisos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    function modalPermisos(menuPermisoId,operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('conf-general/admin-permisos/form/nuevo/permiso'); ?>',
                type: 'POST',
                data: {menuPermisoId: menuPermisoId, operacion: operacion, menuId: '<?= $menuId; ?>', menu: '<?= $menu; ?>'}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalPermisos').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

     $(document).ready(function() {
        $('#btnRegresar').on('click', function() {
            // Redireccionar a la URL correspondiente
            window.location.href = '<?php echo base_url('conf-general/admin-modulos/vista/modulos/menus/'.$modulo['moduloId'].'/'.$modulo['modulo']); ?>';
        });

        $('#tablaPermisos').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-permisos/tabla/permisos'); ?>',
                "data": {
                    menuId: '<?= $menuId; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 }, 
                { "width": "15%", "targets": 3 }  
            ],
            "language": {
                "url": "../../../../../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>
<?= $this->endSection(); ?>