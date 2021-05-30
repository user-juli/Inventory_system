<?php
  $page_title = 'Agregar Clientes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  
?>
<?php
  if(isset($_POST['add_customer'])){

   $req_fields = array('idcustomer','name','address','phone' );
   validate_fields($req_fields); 

   if(empty($errors)){
        $id   = remove_junk($db->escape((int)$_POST['idcustomer']));
        $name   = remove_junk($db->escape($_POST['name']));
        $add   = remove_junk($db->escape($_POST['address']));
        $pho = remove_junk($db->escape($_POST['phone']));
        //$password = sha1($password);
        $query = "INSERT INTO customers (";
        $query .="id,name,address,phone";
        $query .=") VALUES (";
        $query .=" {$id}, '{$name}', '{$add}', '{$pho}'";
        $query .=")";
        
        if($db->query($query)){
          //sucess
          $session->msg('s'," Cliente registrado con exito");
          redirect('add_customer.php', false);
        } else {
          //failed
          $session->msg('d',' No se pudo registrar cliente.');
          redirect('add_customer.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_customer.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="fas fa-th"></span>
        <span>Agregar Cliente</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-6">
        <form method="post" action="add_customer.php">
          <div class="form-group">
              <label for="idcustomer">Cedula</label>
              <input type="text" class="form-control" name="idcustomer" placeholder="Cedula" required>
          </div>
          <div class="form-group">
              <label for="name">Nombres</label>
              <input type="text" class="form-control" name="name" placeholder="Nombre y Apellido">
          </div>
          <div class="form-group">
              <label for="address">Direccion</label>
              <input type="text" class="form-control" name ="address"  placeholder="Direccion">
          </div>
          <div class="form-group">
              <label for="phone">Telefono</label>
              <input type="text" class="form-control" name ="phone"  placeholder="Telefono">
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="add_customer" class="btn btn-primary">Guardar</button>
          </div>
      </form>
      </div>

    </div>

  </div>

<?php include_once('layouts/footer.php'); ?>
