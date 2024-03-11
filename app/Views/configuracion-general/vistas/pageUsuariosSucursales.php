<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    $param1 = $request->getGet('nombre');
?>
<h2>Asignación de sucursales a usuario: <?php echo  $param1;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12">
        <button type= "button" id="btnRegresarUsuarios" class="btn btn-primary estilo-btn">
            <i class="fas fa-save"></i>
            Volver a usuarios
        </button>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#btnRegresarUsuarios').on('click', function() {
            // Redireccionar a la URL correspondiente
            window.location.href = '<?php echo base_url('conf-general/administracion-usuarios'); ?>';
        });
    });
</script>
<?= $this->endSection(); ?>