<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    $param1 = $request->getGet('modulo');
?>
<h2>Asignación de menús a módulo: <?php echo  $param1;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarModulo" class="btn btn-primary estilo-btn">
            <i class="fas fa-angle-double-left"> </i>
            Volver a Módulo
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary estilo-btn">
            <i class="fas fa-save"></i>
            Nuevo menú
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" id="tblMenus" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Menú</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tblMenus').DataTable({
        "language": {
            "url": "../../assets/plugins/datatables/js/spanish.json"
        },
        "columnDefs": [
            { "width": "10%", "targets": 0 }, 
            { "width": "40%", "targets": 1 }, 
            { "width": "35%", "targets": 2 }, 
            { "width": "15%", "targets": 3 }  
        ]
    });
        $('#btnRegresarModulo').on('click', function() {
            // Redireccionar a la URL correspondiente
            window.location.href = '<?php echo base_url('conf-general/administracion-modulos'); ?>';
        });
        $('#btnAbrirModal').on('click', function() {
            // Realizar una petición AJAX para obtener el contenido de la modal
            $.ajax({
                url: '<?php echo base_url('administracion-modulos/nuevo-menu'); ?>',
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