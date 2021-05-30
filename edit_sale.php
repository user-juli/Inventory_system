<?php
  $page_title = 'Editar Venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
$sale = find_by_id('sales',(int)$_GET['id']);
$details = find_edit_Allsale($sale['code']);
if(!$sale){
  $session->msg("d","Venta no se encuentra registrada.");
  redirect('sales.php');
}
?>
<?php 
  $products = find_all('products'); 
  $customers = find_all('customers');
  $pays = find_all('way_to_pay');
  $user = find_by_id('users',$sale['user_name']);
?>
<?php 

  if(isset($_POST['update_sale'])){
    $req_fields = array('c_id','pay','total_s','qy','total' );
    validate_fields($req_fields);
        if(empty($errors)){
          $code      = $sale['code'];
          $c_id      = $db->escape($product['c_id']);
          $pay       = $db->escape((int)$_POST['pay']); 
          $s_total   = $db->escape($_POST['total']);
          $qy        = $db->escape($_POST['qy']);
          $total     = $db->escape($_POST['total']);

          $sql  = "UPDATE sales SET";
          $sql .= " customer_id= '{$c_id}',pay_id={$pay},total_sale='{$s_total}' ";
          $sql .= " WHERE code ='{$code}'";

          $result = $db->query($sql);
          if( $result && $db->affected_rows() === 1){
            update_product_qty($s_qty,$p_id);
            $session->msg('s',"Sale updated.");
            redirect('edit_sale.php?id='.$sale['id'], false);
          } else {
            $session->msg('d',' Sorry failed to updated!');
            redirect('sales.php', false);
          }
        } else {
           $session->msg("d", $errors);
           redirect('edit_sale.php?id='.$sale['id'],false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
  <div class="panel">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Ventas</span>
     </strong>
     <div class="pull-right">
       <a href="sales.php" class="btn btn-primary">Ver Ventas</a>
     </div>
    </div>
    <div class="panel-body">
        <form method="post" action="edit_sale.php" >
         <table class="table table-bordered" id="tabla">
           <thead>
            <th> Producto </th>
            <th> Cantidad </th>
            <th> Precio </th>
            <th> IVA </th>
            <th> SubTotal </th>
            <th> Fecha </th>
           </thead>
            <div class="row">
              <div class="col-md-6">
                <label>Numero Factura</label>
                <input type="text" class="form-control" name="s_id" value="<?php echo $sale['code']; ?>" > 
              </div>
              <div class="col-md-6">
                <label>Vendedor:</label>
                <input type="text" class="form-control" value="<?php echo remove_junk(ucfirst($user['name'])); ?>" readonly>
                <input type="text" class="form-control" name="user" value="<?php echo remove_junk(ucfirst($sale['user_name'])); ?>" readonly>
              </div>
            </div> 
            <tbody  id="product_info"> 
            <?php foreach ($details as $detail): ?>
            <tr>
              <form method="post" action="edit_sale.php?id=<?php echo $sale['id']; ?>">
                <td>
                  <input type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($detail['product_id']); ?>">
                </td>
                <td>
                  <input type="text" class="form-control" name="qy" value="<?php echo $detail['qy'] ?>">
                </td>
                <td>
                  <input type="text" class="form-control" name="price"  >
                </td>
                <td>
                  <input type="text" class="form-control" name="iva" value="<?php echo remove_junk($sale['iva']); ?>" >
                </td>
                <td>
                  <input type="text" class="form-control" name="total" value="<?php echo $detail['total'] ?>">
                </td>
                <td>
                  <input type="date" class="form-control" name="date" value="<?php echo remove_junk(ucwords($sale['date'])); ?>">
                </td>
              </form>
              </tr>
              <?php endforeach;?>
            </tbody> 
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="c_id">Cliente</label>
                    <select class="form-control" name="c_id">
                      <?php  foreach ($customers as $customer): ?>
                      <option <?php if($customer['id'] === $sale['customer_id']) echo 'selected="selected"';?> value="<?php echo (int)$customer['id'] ?>">
                        <?php echo ucwords($customer['name']);?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6"> 
                    <label for="pay">Forma de Pago</label>
                    <select class="form-control" name="pay">
                    <?php  foreach ($pays as $pay): ?>
                      <option <?php if( $pay['id'] === $sale['pay_id']) echo 'selected="selected"';?> value="<?php echo (int)$pay['id'] ?>"><?php echo ucwords($pay['description']);?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>  
                </div>
              </div> 
            <tr>
              <th colspan="4"><strong style="font-size: 12px; color: #222222; aling='right'; "> Total: </strong></th>
              <td colspan="1"><strong style="font-size: 12px; color: #222222;"><input type="text" class="form-control" name="total" id="total" value="<?php echo remove_junk(ucwords($sale['total_sale'])); ?>"></td>
              <td colspan="1"><button type="button" class="btn btn-danger" id="descuento">Aplicar Descuento</button></td>
            </tr>
         </table>
         <button type="submit" name="update_sale" class="btn btn-primary">Actualizar Venta</button>
    
    </div>
  </div>
  </div>

</div> 

<?php include_once('layouts/footer.php'); ?>
