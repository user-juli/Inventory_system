<?php
  $page_title = 'Agregar Proveedores';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $cities = find_all('cities');
?>
<?php
  if(isset($_POST['add_provider'])){

   $req_fields = array('idprov','name','trade','address','phone','city' );
   validate_fields($req_fields); 

   if(empty($errors)){
        $id   = remove_junk((int)$db->escape($_POST['idprov']));
        $name   = remove_junk($db->escape($_POST['name']));
        $trade   = remove_junk($db->escape($_POST['trade']));
        $add   = remove_junk($db->escape($_POST['address']));
        $pho = remove_junk($db->escape($_POST['phone']));
        $cty = (int)$db->escape($_POST['city']);
        
        $query = "INSERT INTO providers (";
        $query .="id,name,tradename,address,phone,city_id";
        $query .=") VALUES (";
        $query .=" '{$id}', '{$name}', '{$trade}', '{$add}', '{$pho}', '{$cty}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s'," Proveedor registrado con exito");
          redirect('add_provider.php', false);
        } else {
          //failed
          $session->msg('d',' No se pudo registrar proveedor.');
          redirect('add_provider.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_provider.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="fas fa-th"></span>
          <span>Agregar Proveedor</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="add_provider.php">
            <div class="form-group">
                <label for="idprov">Cedula</label>
                <input type="text" class="form-control" name="idprov" placeholder="Cedula" required>
            </div>
            <div class="form-group">
                <label for="name">Nombres</label>
                <input type="text" class="form-control" name="name" placeholder="Nombre y Apellido">
            </div>
            <div class="form-group">
                <label for="trade">Nombre Comercial</label>
                <input type="text" class="form-control" name="trade" placeholder="Nombre Comercial">
            </div>
            <div class="form-group">
                <label for="address">Direccion</label>
                <input type="text" class="form-control" name ="address"  placeholder="Direccion">
            </div>
            <div class="form-group">
                <label for="phone">Telefono</label>
                <input type="text" class="form-control" name ="phone"  placeholder="Telefono">
            </div>
            <div class="form-group">
              <label for="city">Ciudad</label>
                <select class="form-control" name="city">
                  <?php foreach ($cities as $city ):?>
                   <option value="<?php echo $city['id'];?>"><?php echo ucwords($city['name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_provider" class="btn btn-primary">Guardar</button>
            </div>
        </form>
        </div>

      </div>

    </div>

<?php include_once('layouts/footer.php'); ?>
