<?php
    if($operacion == "editar") {
        $mensajeAlerta = "cliente actualizado con éxito";
    } else {
        $mensajeAlerta = "cliente creado con éxito";
    }
?>
<form id="frmModal" method="post" action="<?php echo base_url('ventas/admin-clientes/operacion/guardar/clientes'); ?>">
    <div id="modalClientes" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo ($operacion == 'editar' ? 'Editar cliente' : 'Nuevo cliente');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
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
                            <div class="form-outline">
                                <input type="text" id="cliente" name="cliente" class="form-control" value="" required>
                                <label class="form-label" for="cliente">Nombre del cliente</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="clienteComercial" name="clienteComercial" class="form-control numero" value="" required>
                                <label class="form-label" for="clienteComercial">Nombre comercial</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                        <div class="form-select-control">
                                <select name="selectTipoContribuyente" id="selectTipoContribuyente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($tipoContribuyente as $tipoContribuyente){ ?>
                                        <option value="<?php echo $tipoContribuyente['tipoContribuyenteId']; ?>"><?php echo $tipoContribuyente['tipoContribuyente']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="nrcCliente" name="nrcCliente" class="form-control" min ="0" value="">
                                <label class="form-label" for="nrcCliente">NRC</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                        <div class="form-select-control">
                                <select name="selectActividadEconomica" id="selectActividadEconomica" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($actividadEconomica as $actividadEconomica){ ?>
                                        <option value="<?php echo $actividadEconomica['actividadEconomicaId']; ?>"><?php echo $actividadEconomica['actividadEconomica']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                        <div class="form-select-control">
                                <select name="selectTipoDocumento" id="selectTipoDocumento" style="width: 100%;" required>
                                    <option value=""></option>
                                    <?php foreach ($documentoIdentificacion as $documentoIdentificacion){ ?>
                                        <option value="<?php echo $documentoIdentificacion['documentoIdentificacionId']; ?>"><?php echo $documentoIdentificacion['documentoIdentificacion']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="numeroDocumento" name="numeroDocumento" class="form-control" value="" min ="0" required>
                                <label class="form-label" for="numeroDocumento">Numero del documento</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="form-select-control">
                            <select name="selectPaisCliente" id="selectPaisCliente" style="width: 100%;" required>
                                <option value="1">El Salvador</option>
                                <?php foreach ($pais as $pais) { ?>
                                    <option value="<?php echo $pais['paisId']; ?>"><?php echo $pais['pais']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-select-control">
                            <select name="selectDepartamentoCliente" id="selectDepartamentoCliente" style="width: 100%;" required>
                                <option value=""></option>
                                <?php foreach ($paisCiudad as $paisCiudad) { ?>
                                    <option value="<?php echo $paisCiudad['paisCiudadId']; ?>"><?php echo $paisCiudad['paisCiudad']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectMunicipioCliente" id="selectMunicipioCliente" style="width: 100%;" required>
                                <option value=""></option>
                                <?php foreach ($paisEstado as $paisEstado) { ?>
                                    <option value="<?php echo $paisEstado['paisEstadoId']; ?>"><?php echo $paisEstado['paisEstado']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="direccionCliente" name="direccionCliente" class="form-control" min ="0" value="">
                                <label class="form-label" for="selectDireccionCliente">Dirección del cliente</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="correoElectronico" name="correoElectronico" class="form-control" min ="0" value="">
                                <label class="form-label" for="selectCorreoElectronico">Correo electronico</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnguardarCliente" class="btn btn-primary">
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
            dropdownParent: $('#modalClientes')
        });

        $("#selectTipoPersona").select2({
            placeholder: 'Tipo persona',
            dropdownParent: $('#modalClientes')
        });

        $("#selectTipoDocumento").select2({
            placeholder: 'Tipo documento',
            dropdownParent: $('#modalClientes')
        });
        $("#selectTipoContribuyente").select2({
            placeholder: 'Tipo contribuyente',
            dropdownParent: $('#modalClientes')
        });

        $("#selectPaisCliente").select2({
            placeholder: 'Pais',
            dropdownParent: $('#modalClientes')
        });
        $("#selectDepartamentoCliente").select2({
            placeholder: 'Departamento',
            dropdownParent: $('#modalClientes')
        });
        $("#selectMunicipioCliente").select2({
            placeholder: 'Municipio',
            dropdownParent: $('#modalClientes')
        });

        $("#selectActividadEconomica").select2({
            placeholder: 'Actividad economica',
            dropdownParent: $('#modalClientes')
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
                        $('#modalClientes').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $mensajeAlerta; ?>',
                            text: response.mensaje
                        }).then((result) => {
                            $("#tablaClientes").DataTable().ajax.reload(null, false);
                            
                        });
                        console.log("Último ID insertado:", response.clienteId);
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
            $("#selectTipoPersona").val('<?= $campos["tipoPersonaId"]; ?>').trigger("change");
            $("#selectTipoContribuyente").val('<?= $campos["tipoContribuyenteId"]; ?>').trigger("change");
            $("#selectTipoDocumento").val('<?= $campos["documentoIdentificacionId"]; ?>').trigger("change");
            $("#selectActividadEconomica").val('<?= $campos["actividadEconomicaId"]; ?>').trigger("change");

    });
</script>
