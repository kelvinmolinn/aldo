<form id="frmModal">
    <div id="modalPrecios" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= ('Actualizar precio de venta'); ?></h5>
                </div>
                <div class="modal-body">

                    <!-- Botón para mostrar el input -->
                <button id="mostrarInputBtn" class="btn btn-primary" type="button">Nuevo Precio</button>

                <!-- Div para contener el input (inicialmente oculto) -->
                <div id="inputContainer" style="display: none;">
                  <label for="nuevoPrecio">Ingrese el nuevo precio:</label>
                  <input type="number" id="nuevoPrecio" name="nuevoPrecio" disabled>
                  <button class="btn btn-primary" id="guardarPrecioBtn">Guardar</button>
                </div>

                <hr>
                <div class= "table-responsive">
                    <table id="tblPrecio" name = "tblPrecio" class="table table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Costos anteriores</th>
                                <th>Precios anteriores</th>
                                <th>Fecha y hora del cambio</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {

         // Event listener para mostrar el input
  $("#mostrarInputBtn").click(function() {
    // Muestra el div contenedor del input
    $("#inputContainer").show();
    // Desbloquea el input
    $("#nuevoPrecio").prop("disabled", false);
  });

  // Event listener para guardar el precio
  $("#guardarPrecioBtn").click(function() {
    // Obtiene el valor ingresado en el input
    var nuevoPrecio = $("#nuevoPrecio").val();
    
    // Aquí podrías realizar cualquier acción con el nuevo precio, como enviarlo a un servidor, etc.
    console.log("Nuevo precio ingresado: " + nuevoPrecio);
    
    // Oculta el div contenedor del input
    $("#inputContainer").hide();
    // Bloquea nuevamente el input
    $("#nuevoPrecio").prop("disabled", true);
  });

        $('#tblPrecio').DataTable({
            "ajax": {
                "method": "POST",
                "url": '<?php echo base_url('inventario/admin-producto/tabla/precio'); ?>',
                "data": {
                    x: ''
                }
            },
            "columnDefs": [
                { "width": "10%"}, 
                { "width": "30%"}, 
                { "width": "30%"},
                { "width": "30%"} 
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