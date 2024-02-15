    <?php echo $this->extend('plantilla/layout'); ?>
    <?php echo $this->section('contenido'); ?>
    
    <table>
        <thead>
            <th>Nombre</th>
            <th>Stock</th>
            <th>precio</th>
        </thead>
        <tbody></tbody>
    </table>

    <?php echo $this->endSection(); ?>

    <?php echo $this->section('script'); ?>

    <script>
        alert("hola")
    </script>

    <?php echo $this->endSection(); ?>
