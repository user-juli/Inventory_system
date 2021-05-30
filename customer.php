<?php
  $page_title = 'Lista de usuarios';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(3);
//pull out all user form database
 $all_customers = find_all_customer();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="fas fa-th"></span>
          <span>Clientes</span>
       </strong>
         <a href="add_customer.php" class="btn btn-info pull-right">Agregar</a>
      </div>
     <div class="panel-body table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th style="width: 20%;">Cedula </th>
            <th style="width: 20%;">Nombres</th>
            <th style="width: 20%;">Direccion</th>
            <th style="width: 20%;">Telefono</th>
            <th class="text-center" style="width: 100px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_customers as $a_customer): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><?php echo remove_junk($a_customer['id'])?></td>
           <td class="text-center"><?php echo remove_junk($a_customer['name'])?></td>
           <td class="text-center"><?php echo remove_junk($a_customer['address'])?></td>
           <td class="text-center"><?php echo remove_junk($a_customer['phone'])?></td>
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_customer.php?id=<?php echo (int)$a_customer['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                  <i class="fas fa-edit"></i>
               </a>
                <a href="delete_customer.php?id=<?php echo (int)$a_customer['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
                  <i class="fas fa-trash"></i>
                </a>
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
  <?php include_once('layouts/footer.php'); ?>
