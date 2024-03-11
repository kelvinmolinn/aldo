<?= 
    $this->extend('Panel/plantilla'); 
    $this->section('contenido');

    $param1 = $request->getGet('nombre');
?>
<h2>Asignación de sucursales a usuario: <?php echo  $param1;?></h2>
<hr>
<div class="row mb-4">
    <div class="col-md-6">
        <button type= "button" id="btnRegresarUsuarios" class="btn btn-secondary">
            <i class="fas fa-angle-double-left"></i>
            Volver a usuarios
        </button>
    </div>
    <div class="col-md-6 text-right">
        <button type= "button" id="btnAbrirModal" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Asignar sucursal
        </button>
    </div>
</div>
<div class="table-responsive">
    <table id="tblEmpleados" class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Sucursal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $n = 0;
                //var_dump($empleados);
                //foreach($empleados as $empleados){ 
                    $n++;
            ?>
                <tr>
                    <td><?php echo $n; ?></td>
                        <td><b>Sucursal: </b>
                    </td>
                    <td>
                        <button class="btn btn-primary mb-1" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-pencil-alt"></i>
                            <span class=""></span>
                        </button>
                        
                    </td>
                </tr>
            <?php //} ?>
        </tbody>
    </table>
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