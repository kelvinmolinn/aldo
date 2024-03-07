<div id="modalUsuario" class="modal" tabindex="-1">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo usuario</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="number" class="form-control" placeholder="DUI">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline">
                    <input type="date" class="form-control" placeholder="Fecha de nacimiento">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-select-control">
                    <select name="selectGenero" id="selectGenero" style = "width: 100%;">
                        <option value=""></option>
                        <option value="hombre">Hombre</option>
                        <option value="mujer">Mujer</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Primer nombre">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Segundo nombre">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Primer apellido">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="text" class="form-control" placeholder="Segundo apellido">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-outline">
                    <input type="email" class="form-control" placeholder="DUI">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-select-control">
                    <select name="selectRol" id="selectRol" style = "width: 100%;">
                        <option value=""></option>
                        <option value="1">Jefe</option>
                        <option value="2">Jefe 2</option>
                    </select>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $("#selectGenero").select2({
            placeholder: 'Genero'
        })
        $("#selectRol").select2({
            placeholder: 'Roles'
        })
    });
</script>