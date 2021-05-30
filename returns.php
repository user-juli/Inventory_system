<?php
  $page_title = 'Agregar venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $customers = find_all('customers');
   $pays = find_all('way_to_pay');
?>

<?php

  if(isset($_POST['return'])) {
    //$req_fields = array('s_id''s_price','s_iva','s_date','name','s_cid','s_pay');
    //validate_fields($req_fields);

        if(empty($errors)){
          $factura = $_POST['s_id'];
          $producto = $_POST['p_id'];
          $cantidad = $_POST['qy'];
          $total = $_POST['tot'];
          $fecha = date('Y-m-d');
          
          $cadena = "INSERT INTO returns (id,details_sale,details_product,date,qy,details_total) VALUES ";

          for($i=0; $i<count($factura); $i++){
            $cadena.="(null,'".$factura[$i]."','".$producto[$i]."','".$fecha."',".$cantidad[$i].",".$total[$i]."),";
                      
          }

          $cadena_final = substr($cadena, 0, -1);
          $cadena_final.= ";";

          if($db->query($cadena_final)){
            $i=0;

            while ($i < count($producto)) {
              $cant = $_POST['qy'][$i];
              $prod = $_POST['p_id'][$i];
  
              $update = $db->query("UPDATE products SET stock=stock + $cant WHERE code = '$prod' LIMIT 1");
  
              ++$i;
            }
            if($update){
              $session->msg('s',"Devolucion Realizada ");
              redirect('return_report.php', false);
              
            }
          } else {
            $session->msg('d','Lo siento, Devoluvion no pudo ser realizada.');
            redirect('returns.php', false);
          }
             
    }
    
  } 

?>

<?php include_once('layouts/header.php'); ?>

<div class="row" id="ventas"> 

  <div class="row">
    <div class="col-md-6">
      <?php echo display_msg($msg); ?>
      <form method="post" action="ajax.php" autocomplete="off" id="fact-form">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary" id="add" >Buscar</button>
              </span>
              <input type="text" id="fact_input" class="form-control" name="title"  placeholder="Buscar por factura">
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
        <form method="post" action="returns.php" >
         <table class="table table-bordered" id="tabla">
           <thead>
            <th> Factura </th>
            <th> Producto </th>
            <th> Cantidad </th>
            <th> Precio </th>
            <th> Total </th>
            <th> Fecha </th>
            <th> Acciones </th>
           </thead>
            
            <tbody  id="sale_info"> 
              
            </tbody> 
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                    <label>Vendedor:</label>
                    <input type="text" class="form-control" value="" readonly>
                  </div>
                  <div class="col-md-4">
                    <label for="s_cid">Cliente</label>
                    <input type="text" class="form-control" value="" readonly>
                  </div>
                  <div class="col-md-4"> 
                    <label for="s_pay">Forma de Pago</label>
                    <input type="text" class="form-control" value="" readonly>
                  </div> 

                </div>
              </div> 
          </table>
          
          
          <button type="submit" name="return" class="btn btn-success" >Devolver</button>

        </form>
      </div>
    </div>

  </div>


<?php include_once('layouts/footer.php'); ?>