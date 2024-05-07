<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Proveedor actualizado con éxito";
    } else {
        $mensajeAlerta = "Proveedor creado con éxito";
    }
?>
<form id="frmModal">
    <div id="modalProveedores" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoProveedor" id="selectTipoProveedor" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Local">Proveedor local</option>
                                    <option value="Internacional">Proveedor Internacional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoPersona" id="selectTipoPersona" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Local">Proveedor local</option>
                                    <option value="Internacional">Proveedor Internacional</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoContribuyente" id="selectTipoContribuyente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Local">Proveedor local</option>
                                    <option value="Internacional">Proveedor Internacional</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarProveedor" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar
                    </button>
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
        $("#selectTipoProveedor").select2({
            placeholder: 'Tipo proveedor'
        });

        $("#selectTipoPersona").select2({
            placeholder: 'Tipo persona'
        });

        $("#selectTipoContribuyente").select2({
            placeholder: 'Tipo contribuyente'
        });
    });
</script>
