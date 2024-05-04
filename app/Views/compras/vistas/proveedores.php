<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

?>
<h2>Proveedores</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn">
            <i class="fas fa-save"></i>
            Nuevo permiso
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tablaProveedores" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Proveedores</th>
                <th>Documentos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tablaProveedores').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('conf-general/admin-permisos/tabla/permisos'); ?>',
                "data": {
                    x:''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "35%", "targets": 2 }, 
                { "width": "20%", "targets": 3 } 
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