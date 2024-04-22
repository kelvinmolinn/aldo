<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    //$param1 = $request->getGet('modulo');
?>
<h2>Permisos del rol: <?php echo  $rol;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarRol" class="btn btn-secondary estilo-btn">
            <i class="fas fa-angle-double-left"> </i>
            Volver a Módulo
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary estilo-btn" onclick="">
            <i class="fas fa-save"></i>
            Nuevo menú
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
    $(document).ready(function() {
        $('#btnRegresarRol').on('click', function() {
            // Redireccionar a la URL correspondiente
            window.location.href = '<?php echo base_url('conf-general/admin-roles/index'); ?>';
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
                { "width": "35%"}, 
                { "width": "15%"}  
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