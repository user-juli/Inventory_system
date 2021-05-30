<?php
  $page_title = 'Ciudades';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_cities = find_all('cities')
?>
<?php
 if(isset($_POST['add_city'])){
   $req_field = array('city-name');
   validate_fields($req_field);
   $city_name = remove_junk($db->escape($_POST['city-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO cities (name)";
      $sql .= " VALUES ('{$city_name}')";
      if($db->query($sql)){
        $session->msg("s", "Ciudad agregada exitosamente.");
        redirect('city.php',false);
      } else {
        $session->msg("d", "Lo siento, registro falló");
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
  </div>
   
  <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="fas fa-th"></span>
            <span>Agregar categoría</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="city.php">
            <div class="form-group">
                <input type="text" class="form-control" name="city-name" placeholder="Nombre de la ciudad" required>
            </div>
            <button type="submit" name="add_city" class="btn btn-primary">Agregar ciudad</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="fas fa-th"></span>
          <span>Lista de ciudades</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Ciudades</th>
                    <th class="text-center" style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_cities as $city):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($city['name'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_city.php?id=<?php echo (int)$city['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                          <span class="fas fa-edit"></span>
                        </a>
                        <a href="delete_city.php?id=<?php echo (int)$city['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
                          <span class="fas fa-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
