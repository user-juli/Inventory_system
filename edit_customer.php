<?php
  $page_title = 'Editar Cliente';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $e_customer = find_by_id('customers',(int)$_GET['id']);
  if(!$e_customer){
    $session->msg("d","Cliente no existe en la base de datos");
    redirect('customer.php');
  }
?>

<?php
//Update User basic info 
  if(isset($_POST['update'])) {
    $req_fields = array('name','address','phone');
    validate_fields($req_fields);
    if(empty($errors)){
             $id = (int)$e_customer['id'];
           $name =  remove_junk($db->escape($_POST['name']));
        $address =  remove_junk($db->escape($_POST['address']));
          $phone =  remove_junk($db->escape($_POST['phone']));

          $sql = "UPDATE customers SET name ='{$name}', address='{$address}', phone='{$phone}' WHERE id='{$db->escape($id)}' ";
         $result = $db->query($sql);
          if($result && $db->affected_rows() === 1){
            $session->msg('s',"Cliente Actualizado ");
            //redirect('edit_customer.php?id='.(int)$e_customer['id'], false);
            redirect('customer.php');
          } else {
            $session->msg('d',' Lo siento no se actualizó los datos.');
            redirect('edit_customer.php?id='.(int)$e_customer['id'], false);
          }
    } else {
      $session->msg("d", $errors);
      redirect('edit_customer.php?id='.(int)$e_customer['id'],false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>
 <div class="row">
   <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
  <div class="col-md-6">
     <div class="panel panel-default">
       <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Actualiza Cliente <?php echo remove_junk(ucwords($e_customer['name'])); ?>
        </strong>
       </div>
       <div class="panel-body">
          <form method="post" action="edit_customer.php?id=<?php echo (int)$e_customer['id'];?>" class="clearfix">
            <div class="form-group">
              <label for="name" class="control-label">Nombres</label>
              <input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($e_customer['name'])); ?>">
            </div>
            <div class="form-group">
              <label for="address" class="control-label">Direccion</label>
              <input type="text" class="form-control" name="address" value="<?php echo remove_junk(ucwords($e_customer['address'])); ?>">
            </div>
            <div class="form-group">
              <label for="phone" class="control-label">Telefono</label>
              <input type="text" class="form-control" name="phone" value="<?php echo remove_junk(ucwords($e_customer['phone'])); ?>">
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="update" class="btn btn-info">Actualizar</button>
            </div>
        </form>
       </div>
     </div>
  </div>

 </div>
<?php include_once('layouts/footer.php'); ?>
