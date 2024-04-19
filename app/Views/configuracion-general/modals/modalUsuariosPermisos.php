<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Usuarios con el permiso: </h2>
<hr>
<div class="table-responsive">
    <table class="table table-hover" id="tablaUsuariosPermisos" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Usuarios</th>
                <th>Roles</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
     $(document).ready(function() {
        $('#tablaPermisos').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-permisos/tabla/usuarios/permiso'); ?>',
                "data": {
                    menuId: '<?= $menuId; ?>'
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 } 
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