<h2>Continuar descargo N°. </h2>
<hr>
<div class="row mb-4">
<div class="col-md-6">
        <button type= "button" id="btnRegresarDescargo" class="btn btn-secondary estilo-btn">
        <i class="fas fa-backspace "></i>
            Volver 
        </button>
    </div>
    <div class="col-md-12 text-right">
        <button type= "button" id="btnAbrirModalProducto" class="btn btn-primary" onclick="modalAgregarProducto(0, 'insertar');">
            <i class="fas fa-plus nav-icon"></i>
            Agregar producto
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        tituloVentana("Descargos/Continuar salida");
        $('#btnRegresarDescargo').on('click', function() {
            cambiarInterfaz('inventario/admin-salida/index', {renderVista: 'No'});
        });
    });
</script>