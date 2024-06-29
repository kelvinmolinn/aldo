<?php
    /*if($operacion == "editar") {
        $mensajeAlerta = "Proveedor actualizado con éxito";
    } else {
        $mensajeAlerta = "Proveedor creado con éxito";
    }*/
?>
<form id="frmModal" method="post" action="<?php echo base_url(''); ?>">
    <div id="modalClientes" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Nuevo Cliente 
                        <?php //echo ($operacion == 'editar' ? 'Editar Proveedor' : 'Nuevo Proveedor');?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectTipoPersona" id="selectTipoPersona" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Juridica">Juridica</option>
                                    <option value="Natural">Natural</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="nombreCliente" name="nombreCliente" class="form-control" value="" required>
                                <label class="form-label" for="nombreCliente">Nombre del cliente</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-outline">
                                <input type="text" id="nombreComercialCliente" name="nombreComercialCliente" class="form-control numero" value="" required>
                                <label class="form-label" for="nombreComercialCliente">Nombre comercial</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" id="categoriaCliente" name="categoriaCliente" class="form-control" value="">
                                <label class="form-label" for="categoriaCliente">Categoria del cliente</label>
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
                                <select name="actividadEconomicaCliente" id="actividadEconomicaCliente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="Actividad">Otros servicios de información n.c.p.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-select-control">
                                <select name="selectTipoDocumento" id="selectTipoDocumento" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">DUI</option>
                                    <option value="2">NIT</option>
                                    <option value="3">OTRO</option>
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
                                    <option value=""></option>
                                    <option value="1">El Salvador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectDepartamentoCliente" id="selectDepartamentoCliente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">San Salvador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-select-control">
                                <select name="selectMunicipioCliente" id="selectMunicipioCliente" style="width: 100%;" required>
                                    <option value=""></option>
                                    <option value="1">San Salvador</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="selectDireccionCliente" name="selectDireccionCliente" class="form-control" min ="0" value="">
                                <label class="form-label" for="selectDireccionCliente">Dirección del cliente</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <input type="text" id="selectCorreoElectronico" name="selectCorreoElectronico" class="form-control" min ="0" value="">
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

        $("#actividadEconomicaCliente").select2({
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

    });
</script>
