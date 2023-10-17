<?php
foreach($alertas as $key => $alerta):
    foreach($alerta as $mensaje):
?>

        <div class="alerta <?php echo $key; ?>">
        <p><?php echo $mensaje; ?></p>
        </div>

<?php
    endforeach;
endforeach;
?>