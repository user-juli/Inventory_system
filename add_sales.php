<?php
  $page_title = 'Agregar venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $customers = find_all('customers');
   $pays = find_all('way_to_pay');
?>

<?php

  if(isset($_POST['add_sales'])) {
    //$req_fields = array('s_id''s_price','s_iva','s_date','name','s_cid','s_pay');
    //validate_fields($req_fields);

        if(empty($errors)){
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

            if($formap === '2'){ 
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
                          $debts = "INSERT INTO debts (amount_id,status) SELECT a.id, '1' FROM amounts a WHERE sales_code='{$factura}' ";
                          $db->query($debts);
                          $session->msg('s',"Venta agregada ");
                          redirect('add_sales.php', false);
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
        

            }else { 
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
                    $session->msg('s',"Venta agregada ");
                    redirect('add_sales.php', false);
                    
                  }
                } else {
                  $session->msg('d','Lo siento, venta no pudó ser registrada.');
                  redirect('add_sales.php', false);
                }
              } else {
                $session->msg('d','Lo siento, venta no pudó ser registrada.');
                redirect('add_sales.php', false);
              }
            }
          
    }
    
  } 

?>

<?php include_once('layouts/header.php'); ?>

<div class="row" id="ventas"> 

  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary" id="add" >Agregar</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Buscar por el nombre del producto">
          </div>
          <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="fas fa-th"></span>
          <span>Agregar Venta</span>
       </strong>
      </div>
        
      <div class="panel-body">
        <form method="post" action="add_sales.php" >
         <table class="table table-bordered" id="tabla">
           <thead>
            <th> Producto </th>
            <th> Cantidad </th>
            <th> Precio </th>
            <th> IVA </th>
            <th> SubTotal </th>
            <th> Fecha </th>
            <th> Acciones </th>
           </thead>
            <div class="row">
              <div class="col-md-6">
                <label>Numero Factura</label>
                <input type="text" class="form-control" name="s_id"  value="FACT-<?php echo generarCodigo(); ?> " readonly> 
              </div>
              <div class="col-md-6">
                <label>Vendedor:</label>
                <input type="text" class="form-control" value="<?php echo remove_junk(ucfirst($user['name'])); ?>" readonly>
                <input type="hidden" class="form-control" name="user" value="<?php echo remove_junk(ucfirst($user['id'])); ?>" readonly>
              </div>
            </div> 
            <tbody  id="product_info"> 
              
            </tbody> 
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="city">Cliente</label>
                    <select class="form-control" name="s_cid">
                      <?php  foreach ($customers as $customer): ?>
                      <option value="<?php echo (int)$customer['id'] ?>">
                        <?php echo $customer['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6"> 
                    <label for="city">Forma de Pago</label>
                    <select class="form-control" name="s_pay" onChange="pagoOnChange(this)">
                    <?php  foreach ($pays as $pay): ?>
                      <option value="<?php echo (int)$pay['id'] ?>">
                        <?php echo $pay['description'] ?></option>
                    <?php endforeach; ?>
                    </select>
                    <div id="abonar" style="display: none">
                      <label for="bono">Dinero Abonado: </label> <input type="text" class="form-control" name="bono" id="nbono">
                    </div>
                    
                  </div>  
                </div>
              </div> 
            <tr>
              <th colspan="4"><strong style="font-size: 12px; color: #222222; aling='right'; "> Total: </strong></th>
              <td colspan="1"><strong style="font-size: 12px; color: #222222;"><input type="text" class="form-control" name="total" id="total"></td>
              <td colspan="1"><button type="button" class="btn btn-danger" id="descuento" >Aplicar Descuento</button></td>
              
            </tr>
         </table>
          <div class="form-group clearfix" id="dinero">
            <div class="row">
              <div class="col-md-3">
                <label for="bono">Dinero Recibido: </label> <input type="text" class="form-control" id="efe">
              </div>
              <div class="col-md-3">
                <label for="bono">Cambio: </label> <input type="text" class="form-control" id="camb" readonly>
              </div>
            </div>
          </div>
          
          <button type="submit" name="add_sales" class="btn btn-success" >Realizar Venta</button>

        </form>
      </div>
    </div>

  </div>


<?php include_once('layouts/footer.php'); ?>