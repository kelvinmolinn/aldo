<section class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
        <div id = "contenidoGeneral" class="col-sm-12">
            <h1>Escritorio</h1>
        </div>
    </div>

</div><!-- /.container-fluid -->

</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Proveedores</h5>
                                <h3 class="card-text"><?= $totalProveedores; ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-user-friends fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Clientes</h5>
                                <h3 class="card-text"><?= $totalClientes; ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-user-tie fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Reservas pendientes</h5>
                                <h3 class="card-text"><?= $reservasPendientes; ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Facturas pendientes</h5>
                                <h3 class="card-text"><?= $facturasPendientes; ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-life-ring fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Reservas Hoy</h5>
                                <h3 class="card-text">$ <?= number_format($reservasHoy, 2, ".", ",") ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-dollar-sign fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Ventas Hoy</h5>
                                <h3 class="card-text">$ <?= number_format($ventasHoy, 2, ".", ",") ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-dollar-sign fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Producto más vendido (unidades)</h5>
                                <h3 class="card-text"><?= $productoMasVendidoUnidadesNombre . " (" . $productoMasVendidoUnidadesCantidad . ")" ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-box fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Producto más vendido (monto)</h5>
                                <h3 class="card-text"><?= $productoMasVendidoMontoNombre . " ($ " . number_format($productoMasVendidoMontoCantidad, 2, ".", ",") . ")" ?></h3>
                            </div>
                            <div>
                                <i class="fas fa-dollar-sign fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h5>Resumen de reservas</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Reservas: Hoy
                        <span class="text-right">
                            Con IVA: $ <?= number_format($reservasHoyIVA, 2, ".", ",") ?>
                            <br>
                            Sin IVA: $ <?= number_format($reservasHoy, 2, ".", ",") ?>
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Reservas: <?= $nombreMes ?>
                        <span class="text-right">
                            Con IVA: $ <?= number_format($reservasMesIVA, 2, ".", ",") ?>
                            <br>
                            Sin IVA: $ <?= number_format($reservasMes, 2, ".", ",") ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto más reservado (unidades): <?= $productoMasReservadoUnidadesNombre ?>
                        <span class="text-right">
                            Cantidad: <?= number_format($productoMasReservadoUnidadesCantidad, 0, ".", ",") ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto más reservado (monto): <?= $productoMasReservadoMontoNombre ?>
                        <span class="text-right">
                            Con IVA: $ <?= number_format($productoMasReservadoMontoCantidadIVA, 2, ".", ",") ?>
                            <br>
                            Sin IVA: $ <?= number_format($productoMasReservadoMontoCantidad, 2, ".", ",") ?>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>Resumen de ventas</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ventas: Hoy
                        <span class="text-right">
                            Con IVA: $ <?= number_format($ventasHoyIVA, 2, ".", ",") ?>
                            <br>
                            Sin IVA: $ <?= number_format($ventasHoy, 2, ".", ",") ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ventas: <?= $nombreMes ?>
                        <span class="text-right">
                            Con IVA: $ <?= number_format($ventasMesIVA, 2, ".", ",") ?>
                            <br>
                            Sin IVA: $ <?= number_format($ventasMes, 2, ".", ",") ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto más vendido (unidades): <?= $productoMasVendidoUnidadesNombre ?>
                        <span class="text-right">
                            Cantidad: <?= number_format($productoMasVendidoUnidadesCantidad, 0, ".", ",") ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Producto más vendido (monto): <?= $productoMasVendidoMontoNombre ?>
                        <span class="text-right">
                            Con IVA: $ <?= number_format($productoMasVendidoMontoCantidadIVA, 2, ".", ",") ?>
                            <br>
                            Sin IVA: $ <?= number_format($productoMasVendidoMontoCantidad, 2, ".", ",") ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        tituloVentana('Inicio');
    });
</script>