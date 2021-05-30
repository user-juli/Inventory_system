<?php
    require_once('includes/load.php');

    $factura = $_POST['s_id'];
    $cliente = $_POST['s_cid'];
    $vendedor = $_POST['user'];
    $fecha = $_POST['s_date'];
    $formap = $_POST['s_pay'];
    $totalv = $_POST['total'];
    $ivav = $_POST['s_iva'];

    $producto = $_POST['p_id'];
    $cantidad = $_POST['s_qy'];
    $total = $_POST['s_total'];

    $bono = $_POST['bono'];

    $sql = "INSERT INTO sales (id,code,customer_id,user_name,date,pay_id,total_sale,iva) VALUES (null,'".$factura."',".$cliente.",'".$vendedor."','".$fecha."',".$formap.",'".$totalv."','".$ivav."')"; 

    $cadena = "INSERT INTO details_sales (id,sale_id,product_id,qy,total) VALUES ";

    for($i=0; $i<count($producto); $i++){
    $cadena.="(null,'".$factura."','".$producto[$i]."',".$cantidad[$i].",".$total[$i]."),";

    }

    $cadena_final = substr($cadena, 0, -1);
    $cadena_final.= ";";


    $amount = "INSERT INTO amounts (id,amount,date,sales_customer,sales_code) VALUES (null,'".$bono."','".$fecha."',".$cliente.",'".$factura."' )" ;

    $consult = "SELECT id FROM amounts WHERE sales_code='".$factura."' ";

    //$query = "INSERT INTO debts (id,amount_id,status) VALUES (null,".$consult.",1) ";

    if($db->query($sql)){ 
        if($db->query($cadena_final)){
            $i=0;

            while ($i < count($producto)) {
                $cant = $_POST['s_qy'][$i];
                $prod = $_POST['p_id'][$i];

                $update = $db->query("UPDATE products SET stock=stock - $cant WHERE code = '$prod' LIMIT 1");

                ++$i;
            }
            if($update){
                if ($db->query($amount)) {
                    if ($db->query($consult)) {
                        $db->query("INSERT INTO debts (id,amount_id,status) VALUES (null,".$consult.",1);");
                        $session->msg('s',"Venta agregada ");
                        //redirect('add_sales.php', false);
                        header("location: add_sales.php");
                    }
                }
                
            }
        } else {
        $session->msg('d','Lo siento, venta no pudó ser registrada.');
        redirect('add_sales.php', false);
        }
    } else {
        $session->msg('d','Lo siento, venta no pudó ser registrada.');
        redirect('add_sales.php', false);
    }


?>