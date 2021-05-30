<?php
  $page_title = 'Agregar venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $customers = find_all('customers');
   $pays = find_all('way_to_pay');

?>

<?php
  if(isset($_POST['addsales'])) {
    //$req_fields = array('s_id','fact[]','p_id[]','s_qy[]','s_price','s_iva','s_total[]','s_date','name','s_cid','s_pay');
    //validate_fields($req_fields);

        if(empty($errors)){
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
            //$sql1 = "UPDATE products SET stock=stock - ".$cantidad[$i]." WHERE code = '".$producto[$i]."'";
          }

          $cadena_final = substr($cadena, 0, -1);
          $cadena_final.= ";";

          $query = "INSERT INTO debts (id,sales_customer,sales_code) VALUES (null,".$cliente.",'".$factur."') ";

          if($formap === '2'){
            if($db->query($sql)){ 
              if ($db->query($query)) {
                $session->msg('s','Venta registrada.');
                redirect('addssales.php', false);
              } else {
                $session->msg('d','Lo siento, venta no pudó ser registrada.');
                redirect('addssales.php', false);
              }
            } else {
              $session->msg('d','Lo siento, venta no pudó ser registrada.');
              redirect('addsales.php', false);
            }
          } else {
            if($db->query($sql)){ 
              if($db->query($cadena_final)){
                //if($db->query($sql1)){
                  $session->msg('s',"Venta agregada ");
                  redirect('addsales.php', false);
                //}
              } else {
                $session->msg('d','Lo siento, venta no pudó ser registrada.');
                redirect('addsales.php', false);
              }
            } else {
              $session->msg('d','Lo siento, venta no pudó ser registrada.');
              redirect('addsales.php', false);
            }
          }
    }
    
  } 

?>

<?php include_once('layouts/header.php'); ?>

<div class="row" id="ventas"> 

  <div class="row">
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
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Agregar Venta</span>
       </strong>
      </div>
        
      <div class="panel-body">
        <form method="post" action="addsales.php" >
         <table class="table table-bordered" id="tabla">
           <thead>
            <th> Factura </th>
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
                <input type="text" class="form-control" name="s_id"  placeholder="Numero de Factura" requerid> 
              </div>
              <div class="col-md-6">
                <label>Vendedor: <?php echo remove_junk(ucfirst($user['name'])); ?></label>
                <input type="text" class="form-control" name="user" value="<?php echo remove_junk(ucfirst($user['id'])); ?> ">
              </div>
            </div> 
            <tbody  id="product_info"> 
              
            </tbody> 
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="city">Cliente</label>
                    <select class="form-control" name="s_cid">
                      <option value="0">Sin Cliente</option>
                      <?php  foreach ($customers as $customer): ?>
                      <option value="<?php echo (int)$customer['id'] ?>">
                        <?php echo $customer['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6"> 
                    <label for="city">Forma de Pago</label>
                    <select class="form-control" name="s_pay">
                      <option value="">-Seleccione-</option>
                    <?php  foreach ($pays as $pay): ?>
                      <option value="<?php echo (int)$pay['id'] ?>">
                        <?php echo $pay['description'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>  
                </div>
              </div> 
            <tr>
              <th colspan="5"><strong style="font-size: 12px; color: #222222; aling='right'; "> Total: </strong></th>
              <td colspan="1"><strong style="font-size: 12px; color: #222222;"><input type="text" class="form-control" name="total" id="total"></td>
              <td colspan="1"><button class="btn btn-danger" id="descuento">Aplicar Descuento</button></td>
            </tr>
         </table>
          <div class="form-group clearfix">
            <button type="submit" name="addsales" class="btn btn-success">Realizar Venta</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>