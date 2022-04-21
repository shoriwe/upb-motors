<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-gastos.php';


$gastos = connect()->lista_gastos();
?>

<div class="list-container">
    <?php
    foreach ($gastos as $gasto) {
        echo "
<div class='list-entry'>
    <h3 class='black-text'>$gasto->razon</h3>
    <pre style='min-width: 15vw;'></pre>
    <h3 class='black-text'>$$gasto->valor</h3>
</div>
";
    }
    ?>
</div>
