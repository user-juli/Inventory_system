<?php
  $page_title = 'Editar Proveedor';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
   $cities = find_all('cities');
?>
<?php
  $e_provider = find_by_id('providers',(int)$_GET['id']);
  if(!$e_provider){
    $session->msg("d","Dato no existe en la base de datos");
    redirect('provider.php');
  }
?>

<?php
//Update User basic info
  if(isset($_POST['update'])) {
    $req_fields = array('name','nametrade','address','phone','city');
    validate_fields($req_fields);
    if(empty($errors)){
             $id = (int)$e_provider['id'];
           $name = remove_junk($db->escape($_POST['name']));
      $nametrade = remove_junk($db->escape($_POST['nametrade']));
        $address = remove_junk($db->escape($_POST['address']));
          $phone = remove_junk($db->escape($_POST['phone']));
          $city = remove_junk($db->escape($_POST['city']));

            $sql = "UPDATE providers SET id = '{$id}', name ='{$name}', tradename ='{$nametrade}', address ='{$address}', phone ='{$phone}', city_id ='{$city}' WHERE id='{$db->escape($id)}'";
         $result = $db->query($sql);
          if($result && $db->affected_rows() === 1){
            $session->msg('s',"Proveedor Actualizado ");
            //redirect('edit_provider.php?id='.(int)$e_provider['id'], false);
            redirect('provider.php');
          } else {
            $session->msg('d',' Lo siento no se actualizó los datos.');
            redirect('edit_provider.php?id='.(int)$e_provider['id'], false);
          }
    } else {
      $session->msg("d", $errors);
      redirect('edit_provider.php?id='.(int)$e_provider['id'],false);
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
          Actualiza Proveedor <?php echo remove_junk(ucwords($e_provider['name'])); ?>
        </strong>
       </div>
       <div class="panel-body">
          <form method="post" action="edit_provider.php?id=<?php echo (int)$e_provider['id'];?>" class="clearfix">
            <div class="form-group">
                  <label for="name" class="control-label">Nombres</label>
                  <input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($e_provider['name'])); ?>">
            </div>
            <div class="form-group">
                  <label for="name" class="control-label">Nombre Comercial</label>
                  <input type="name" class="form-control" name="nametrade" value="<?php echo remove_junk(ucwords($e_provider['tradename'])); ?>">
            </div>
            <div class="form-group">
                  <label for="address" class="control-label">Direccion</label>
                  <input type="address" class="form-control" name="address" value="<?php echo remove_junk(ucwords($e_provider['address'])); ?>">
            </div>
            <div class="form-group">
                  <label for="phone" class="control-label">Telefono</label>
                  <input type="phone" class="form-control" name="phone" value="<?php echo remove_junk(ucwords($e_provider['phone'])); ?>">
            </div>
            <div class="form-group">
              <label for="city">Ciudad</label>
                <select class="form-control" name="city">
                  <?php foreach ($cities as $city ):?>
                    <option <?php if($city['id'] === $e_provider['city_id']) echo 'selected="selected"';?> value="<?php echo $city['id'];?>"><?php echo ucwords($city['name']);?></option>
                <?php endforeach;?>
                </select>
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
