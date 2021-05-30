<?php
  $page_title = 'Agregar usuarios';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $groups = find_all('user_groups');
  $stores = find_all('stores');
?>
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('name','username','password','level','store' );
   validate_fields($req_fields);

   if(empty($errors)){
        $name   = remove_junk($db->escape($_POST['name']));
        $username   = remove_junk($db->escape($_POST['username']));
        $password   = remove_junk($db->escape($_POST['password']));
        $user_level = (int)$db->escape($_POST['level']);
        $user_store = (int)$db->escape($_POST['store']);
        $password = sha1($password);
        $query = "INSERT INTO users (";
        $query .="name,username,password,user_level,status,store_id";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$password}', '{$user_level}','1','{$user_store}' ";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s'," Cuenta de usuario ha sido creada");
          redirect('add_user.php', false);
        } else {
          //failed
          $session->msg('d',' No se pudo crear la cuenta.');
          redirect('add_user.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_user.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="fas fa-th"></span>
        <span>Agregar usuario</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-6">
        <form method="post" action="add_user.php">
          <div class="form-group">
              <label for="name">Nombre Completo</label>
              <input type="text" class="form-control" name="name" placeholder="Nombres y Apellidos" required>
          </div>
          <div class="form-group">
              <label for="username">Usuario</label>
              <input type="text" class="form-control" name="username" placeholder="Nombre de usuario">
          </div>
          <div class="form-group">
              <label for="password">Contraseña</label>
              <input type="password" class="form-control" name ="password"  placeholder="Contraseña">
          </div>
          <div class="form-group">
            <label for="level">Rol de usuario</label>
              <select class="form-control" name="level">
                <?php foreach ($groups as $group ):?>
                  <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
              <?php endforeach;?>
              </select>
          </div>
          <div class="form-group">
            <label for="level">Almacen</label>
              <select class="form-control" name="store">
                <?php foreach ($stores as $store ):?>
                  <option value="<?php echo $store['id'];?>"><?php echo ucwords($store['name']);?></option>
              <?php endforeach;?>
              </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="add_user" class="btn btn-primary">Guardar</button>
          </div>
      </form>
      </div>

    </div>

  </div>

<?php include_once('layouts/footer.php'); ?>
