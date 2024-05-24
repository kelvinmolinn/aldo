<h2>Proveedores</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btnNuevoProveedor" class="btn btn-primary estilo-btn" onclick="modalProveedor(0,'insertar');">
            <i class="fas fa-save"></i>
            Nuevo proveedor
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
    function modalProveedor(proveedorId,operacion) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('compras/admin-proveedores/form/nuevo/proveedor'); ?>',
                type: 'POST',
                data: {proveedorId: proveedorId, operacion: operacion}, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalProveedores').modal('show');
                    
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }

    function modalContactoProveedor(proveedorId,proveedor) {
        // Realizar una petición AJAX para obtener los datos del módulo por su ID
        $.ajax({
                url: '<?php echo base_url('compras/admin-proveedores/form/nuevo/contacto/proveedor'); ?>',
                type: 'POST',
                data: { proveedorId: proveedorId, 
                        proveedor: proveedor
                      }, // Pasar el ID del módulo como parámetro
                success: function(response) {
                    // Insertar el contenido de la modal en el cuerpo de la modal
                    $('#divModalContent').html(response);
                    // Mostrar la modal
                    $('#modalContactoProveedor').modal('show');
                },
            error: function(xhr, status, error) {
                // Manejar errores si los hay
                console.error(xhr.responseText);
            }
        });
    }
    $(document).ready(function() {
        tituloVentana("Proveedores");
        $('#tablaProveedores').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('compras/admin-proveedores/tabla/proveedores'); ?>',
                "data": {
                    x:''
                }
            },
            "columnDefs": [
                { "width": "10%", "targets": 0 }, 
                { "width": "40%", "targets": 1 }, 
                { "width": "30%", "targets": 2 }, 
                { "width": "20%", "targets": 3 } 
            ],
            "language": {
                "url": "../assets/plugins/datatables/js/spanish.json"
            },
                "drawCallback": function(settings) {
                // Inicializar tooltips de Bootstrap después de cada dibujo de la tabla
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    });
</script>
