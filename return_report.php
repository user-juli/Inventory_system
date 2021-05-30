<?php
  $page_title = 'Lista de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$returns = find_all_return();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div> 
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Historial de Devoluciones</span>
          </strong>
          <div class="pull-right">
            <a href="returns.php" class="btn btn-primary">Devolver</a>
          </div>
        </div>
        <div class="panel-body">
          <table id="tabla" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> N° Factura</th>
                <th> Nombre Producto </th>
                <th class="text-center" style="width: 15%;"> Cantidad</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Fecha </th>
                <th class="text-center" style="width: 100px;"> Acciones </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($returns as $return):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($return['details_sale']); ?></td>
               <td><?php echo remove_junk($return['details_product']); ?></td>
               <td><?php echo (int)$return['qy']; ?></td>
               <td><?php echo remove_junk($return['details_total']); ?></td>
               <td><?php echo $return['date']; ?></td>
               <td class="text-center">
                  <div class="btn-group">
                    <a href="delete_return.php?id=<?php echo (int)$return['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                       <span class="glyphicon glyphicon-trash"></span>
                     </a>
                  </div>
               </td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>

        <div class="col-md-12 text-center">
          <ul class="pagination pagination-lg pager" id="developer_page"></ul>
        </div>

      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>
