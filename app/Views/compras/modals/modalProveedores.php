<?php
    if($operacion == "editar") {
        $mensajeAlerta = "Proveedor actualizado con éxito";
    } else {
        $mensajeAlerta = "Proveedor creado con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('compras/admin-proveedores/operacion/guardar/proveedores'); ?>">
    <div id="modalProveedores" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="proveedorId" name="proveedorId" value="<?= $campos['proveedorId'] ?>">
                    <input type="hidden" id="operacion" name="operacion" value="<?= $operacion; ?>">
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
                                    <?php foreach ($tipoPersona as $tipoPersona){ ?>
                                        <option value="<?php echo $tipoPersona['tipoPersonaId']; ?>"><?php echo $tipoPersona['tipoPersona']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoContribuyente" id="selectTipoContribuyente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($tipoContribuyente as $tipoContribuyente){ ?>
                                        <option value="<?php echo $tipoContribuyente['tipoContribuyenteId']; ?>"><?php echo $tipoContribuyente['tipoContribuyente']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="nombreProveedor" name="nombreProveedor" class="form-control" value="<?= $campos['proveedor']; ?>" required>
                                <label class="form-label" for="nombreProveedor">Nombre del proveedor</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="nombreComercial" name="nombreComercial" class="form-control numero" value="<?= $campos['proveedorComercial']; ?>" required>
                                <label class="form-label" for="nombreComercial">Nombre comercial</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="nrc" name="nrc" class="form-control" min ="0" value="<?= $campos['ncrProveedor']; ?>">
                                <label class="form-label" for="nrc">NRC</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoDocumento" id="selectTipoDocumento" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($documentoIdentificacion as $documentoIdentificacion){ ?>
                                        <option value="<?php echo $documentoIdentificacion['documentoIdentificacionId']; ?>"><?php echo $documentoIdentificacion['documentoIdentificacion']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="numeroDocumento" name="numeroDocumento" class="form-control" value="<?= $campos['numDocumentoIdentificacion']; ?>" min ="0" required>
                                <label class="form-label" for="numeroDocumento">Numero del documento</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectActividadEconomica" id="selectActividadEconomica" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($actividadEconomica as $actividadEconomica){ ?>
                                        <option value="<?php echo $actividadEconomica['actividadEconomicaId']; ?>"><?php echo $actividadEconomica['actividadEconomica']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="direccionProveedor" name="direccionProveedor" class="form-control " value="<?= $campos['direccionProveedor']; ?>" required>
                                <label class="form-label" for="direccionProveedor">Dirección del proveedor</label>
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
            placeholder: 'Tipo proveedor',
            dropdownParent: $('#modalProveedores')
        });

        $("#selectTipoPersona").select2({
            placeholder: 'Tipo persona',
            dropdownParent: $('#modalProveedores')
        });

        $("#selectTipoContribuyente").select2({
            placeholder: 'Tipo contribuyente',
            dropdownParent: $('#modalProveedores')
        });
        $("#selectTipoDocumento").select2({
            placeholder: 'Tipo de documento',
            dropdownParent: $('#modalProveedores')
        });
        $("#selectActividadEconomica").select2({
            placeholder: 'Actividad economica',
            dropdownParent: $('#modalProveedores')
        });
        document.querySelectorAll('#nrc').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                if (event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '.') {
                    event.preventDefault();
                }
            });
        });

        $("#selectTipoDocumento").change(function(e) {
            if($(this).val() == "1") {
                $('#numeroDocumento').inputmask('99999999-9');
            } else if($(this).val() == "2") {
                $('#numeroDocumento').inputmask('9999-999999-999-9');
            } else if($(this).val() == "3") {
                $('#numeroDocumento').inputmask('remove');
                document.querySelectorAll('#numeroDocumento').forEach(function(input) {
                    input.addEventListener('keydown', function(event) {
                        if (event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '.') {
                            event.preventDefault();
                        }
                    });
                });
            } else if($(this).val() == "4") {
                $('#numeroDocumento').inputmask('remove');
                document.querySelectorAll('#numeroDocumento').forEach(function(input) {
                    input.addEventListener('keydown', function(event) {
                        if (event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '.') {
                            event.preventDefault();
                        }
                    });
                });
            } else {
                $('#numeroDocumento').inputmask('remove');
            }
        });

        $("#frmModal").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'), 
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        // Insert exitoso, ocultar modal y mostrar mensaje
                        $('#modalProveedores').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaProveedores").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.proveedorId);
                    } else {
                        // Insert fallido, mostrar mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores si los hay
                    console.error(xhr.responseText);
                }
            });
        });
            $("#selectTipoProveedor").val('<?= $campos["tipoProveedorOrigen"]; ?>').trigger("change");
            $("#selectTipoPersona").val('<?= $campos["tipoPersonaId"]; ?>').trigger("change");
            $("#selectTipoContribuyente").val('<?= $campos["tipoContribuyenteId"]; ?>').trigger("change");
            $("#selectTipoDocumento").val('<?= $campos["documentoIdentificacionId"]; ?>').trigger("change");
            $("#selectActividadEconomica").val('<?= $campos["actividadEconomicaId"]; ?>').trigger("change");

    });
</script>
