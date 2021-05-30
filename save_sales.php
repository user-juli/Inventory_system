<?php
    require_once('includes/load.php');

    $factur = $_POST['s_id'];
    $cliente = $_POST['s_cid'];
    $vendedor = $_POST['user'];
    $fecha = $_POST['s_date'];
    $formap = $_POST['s_pay'];
    $totalv = $_POST['total'];
    $ivav = $_POST['s_iva'];

    $factura = $_POST['fact'];
    $producto = $_POST['p_id'];
    $cantidad = $_POST['s_qy'];
    $total = $_POST['s_total'];

    $sql = "INSERT INTO sales (id,code,customer_id,user_name,date,pay_id,total_sale,iva) VALUES (null,'".$factur."',".$cliente.",'".$vendedor."','".$fecha."',".$formap.",'".$totalv."','".$ivav."')"; 

    $cadena = "INSERT INTO details_sales (id,sale_id,product_id,qy,total) VALUES ";

    for($i=0; $i<count($factura); $i++){
      $cadena.="(null,'".$factura[$i]."','".$producto[$i]."',".$cantidad[$i].",".$total[$i]."),";
      $sql1 = "UPDATE products SET stock=stock - ".$cantidad[$i]." WHERE code = '".$producto[$i]."'";
    }

    $cadena_final = substr($cadena, 0, -1);
    $cadena_final.= ";";

    
    if($formap === 2){
      echo json_encode(array('error', true));
    } else {
      if($db->query($sql)){ 
        if($db->query($cadena_final)){
          if($db->query($sql1)){
            echo json_encode(array('error', false));
          }
        } else {
          echo json_encode(array('error', true));
        }
      }
    } 

    

    echo json_encode(array('Actualizacion',$sql1));  
     
?>