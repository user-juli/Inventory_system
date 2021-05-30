<?php
  $page_title = 'Editar Ciudad';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  //Display all catgories.
  $city = find_by_id('cities',(int)$_GET['id']);
  if(!$city){
    $session->msg("d","Ciudad no se encuentra.");
    redirect('city.php');
  }
?>

<?php
if(isset($_POST['edit_city'])){
  $req_field = array('city-name');
  validate_fields($req_field);
  $city_name = remove_junk($db->escape($_POST['city-name']));
  if(empty($errors)){
        $sql = "UPDATE cities SET name='{$city_name}'";
       $sql .= " WHERE id='{$city['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Ciudad actualizada con éxito.");
       redirect('city.php',false);
     } else {
       $session->msg("d", "Lo siento, actualización falló.");
       redirect('city.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('city.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editando <?php echo remove_junk(ucfirst($city['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_city.php?id=<?php echo (int)$city['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="city-name" value="<?php echo remove_junk(ucfirst($city['name']));?>">
           </div>
           <button type="submit" name="edit_city" class="btn btn-primary">Actualizar ciudad</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
