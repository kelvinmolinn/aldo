<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');
?>
<h2>Gestión de Módulos</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Nuevo módulo
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Módulo</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    </table>
</div>
<script>
     $(document).ready(function() {
        $('#btnAbrirModal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('administracion-modulos/nuevo-modulo'); ?>',
                type: 'GET',
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                   
                    // Mostrar la modal sin permitir cierre al hacer clic fuera o al presionar "Esc"
                    $('#modalModulos').modal({
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
    });
</script>
<?= $this->endSection(); ?>