<script>
    $(document).ready(function() {
        <?php if ($success) : ?>
            Swal.fire('¡Éxito!', '<?= $mensaje ?>', 'success');
        <?php else : ?>
            Swal.fire('Error', '<?= $mensaje ?>', 'error');
        <?php endif; ?>
        <?php if ($moduloId) : ?>
            // Si hay un móduloId, puedes realizar alguna acción adicional aquí
        <?php endif; ?>
    });
</script>
