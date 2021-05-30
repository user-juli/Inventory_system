<?php
    require_once('includes/load.php');

    $factura = $_POST['factura'];
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $total = $_POST['total'];
    $fecha = date('Y-m-d');

    $cadena = "INSERT INTO returns (id,details_sale,details_product,date,qy,details_total) VALUES (null,'".$factura."','".$producto."','".$fecha."',".$cantidad.",".$total.") ";
    
    $sql = "UPDATE products SET stock=stock + $cantidad WHERE code = '$producto' LIMIT 1 ";

    if($db->query($cadena)){
        $db->query($sql);
    }
     
?>