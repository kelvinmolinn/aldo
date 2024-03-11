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
    <table class="table table-hover" id="miTabla" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Módulo</th>
                <th>Url</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $n = 0;
                //var_dump($empleados);
                foreach($modulos as $modulos){ 
                    $n++;
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><b>Módulo: </b><?php echo $modulos['modulo']; ?><br>
                    </td>
                    <td>
                        <b>Url: </b><?php echo $modulos['urlModulo']; ?>  <br>
                    </td>
                    <td>
                        <button class="btn btn-primary estilo-btn mb-1">
                            <i class="fas fa-pencil-alt"></i> Editar Usuario
                        </button> <br>

                        <button class="btn btn-success estilo-btn mb-1">
                            <i class="fas fa-sync-alt"></i> Restablecer acceso
                        </button> <br>

                        <button class="btn btn-secondary estilo-btn mb-1">
                            <i class="fas fa-store"></i> 0 Sucursales
                        </button> <br>

                        <button class="btn btn-info estilo-btn mb-1">
                            <i class="fas fa-toggle-on"></i> Activar
                        </button> <br>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
     $(document).ready(function() {
        $('#miTabla').DataTable({
        "language": {
            "url": "../../assets/plugins/datatables/js/spanish.json"
        }
    });
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