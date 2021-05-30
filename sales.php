<?php
  $page_title = 'Lista de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sales = find_all_sale();
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
            <span class="fas fa-th"></span>
            <span>Todas la ventas</span>
          </strong>
          <div class="pull-right">
            <a href="add_sales.php" class="btn btn-primary">Agregar venta</a>
          </div>
        </div>
        <div class="panel-body table-responsive">
          <table id="tabla" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> N° Factura</th>
                <th> Nombre Producto </th>
                <th class="text-center" style="width: 15%;"> Cantidad</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Cliente </th>
                <th class="text-center" style="width: 15%;"> Fecha </th>
                <th class="text-center" style="width: 100px;"> Acciones </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['Factura']); ?></td>
               <td><?php echo remove_junk($sale['Producto']); ?></td>
               <td class="text-center"><?php echo (int)$sale['qy']; ?></td>
               <td class="text-center"><?php echo remove_junk($sale['total']); ?></td>
               <td><?php echo remove_junk($sale['cliente']); ?></td>
               <td class="text-center"><?php echo $sale['date']; ?></td>
               <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_sale.php?id=<?php //echo (int)$sale['id'];?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="fas fa-edit"></span>
                    </a>
                    <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="fas fa-trash"></span>
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
