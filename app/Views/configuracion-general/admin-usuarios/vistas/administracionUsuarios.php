<h2>Gestión de usuarios</h2>
<hr>
<div class="row mb-4">
    <div class="col-md-12 text-right">
        <button type= "button" id="btn" class="btn btn-primary" onclick="modal();">
            <i class="fas fa-save"></i>
            Nuevo usuario
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>
</div>

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
                    <select name="selectSexo" id="selectSexo">
                        <option value=""></option>
                        <option value="">Hombre</option>
                        <option value="">Mujer</option>
                    </select>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>

    function modal(){
        $('#modalUsuario').modal(

        );
    }
</script>