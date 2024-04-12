<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Inventario de productos</h2>
<hr>

<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalExistencia(0, 'insertar');">
            <i class="fas fa-save"></i>
            Existencia Inicial
        </button>
        <button type= "button" id="btnAbrirModal" class="btn btn-primary" onclick="modalProducto(0, 'insertar');">
            <i class="fas fa-save"></i>
            Nuevo producto
        </button>
    </div>
</div>
<div class= "table-responsive">
    <table id="tblProducto" name = "tblProducto" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Características</th>
                <th>Precio de venta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
        $(document).ready(function() {
    
    $('#tblProducto').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-producto/tabla/producto'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "20%", "targets": 1 }, 
                { "width": "20%", "targets": 2 },
                { "width": "20%", "targets": 3 },  
                { "width": "30%", "targets": 4 }
            ],
            "language": {
                "url": "../../../assets/plugins/datatables/js/spanish.json"
            },
        });
    });
</script>

<?= $this->endSection(); ?>