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
    <table class="table table-hover" id="" style="width: 100%;">
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

</script>
<?= $this->endSection(); ?>