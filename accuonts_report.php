<?php
  $page_title = 'Abonar Cuenta de Cobro';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $debts = find_by_id('amounts',(int)$_GET['abonar']);
  $customer = debts_accounts($debts['sales_code']);
  $accounts = history_debts($debts['sales_code']);
  if(!$debts){
    $session->msg("d","Cuenta de cobro no se encuentra en la base de datos.");
    redirect('accounts_receivable.php');
  }   
?>


<?php
//Update User basic info
  if(isset($_POST['update'])) {
    $req_fields = array('amount','date','sales_customer','sales_code');
    validate_fields($req_fields);
    if(empty($errors)){
             //$id = (int)$debts['id'];
         $amount = $db->escape($_POST['amount']);
           $date = $db->escape($_POST['date']);
         $salesc = (int)($db->escape($_POST['sales_customer']));
         $salesv = $db->escape($_POST['sales_code']);
       $status   = (int)($db->escape($_POST['status']));

        $query = "INSERT INTO amounts (";
        $query .="id,amount,date,sales_customer,sales_code";
        $query .=") VALUES (";
        $query .="null,'{$amount}', '{$date}', {$salesc},'{$salesv}'";
        $query .=")";
        

        //$sql = "UPDATE debts SET status ='{$name}' WHERE id='{$db->escape($id)}'";

         
          if($db->query($query)){
            //$id = $db->query("SELECT id FROM amounts ORDER BY id DESC LIMIT 1");
            //$row = mysqli_fetch_assoc($id);
             $debts = "INSERT INTO debts (amount_id,status) SELECT id, '{$status}' FROM amounts ORDER BY id DESC LIMIT 1 ";
              $db->query($debts);
            
            $session->msg('s',"Dinero Abonado");
            redirect('accounts_receivable.php', false);
          } else {
            $session->msg('d',' Lo siento no se actualizó los datos.');
            redirect('accuonts_report.php?abonar='.(int)$debts['id'], false);
          }
    } else {
      $session->msg("d", $errors);
      redirect('accuonts_report.php?abonar='.(int)$debts['id'],false);
    }
  }
?>


<?php include_once('layouts/header.php'); ?>

 <div class="row">
   <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
   <div class="form-group clearfix">
   <a href="accounts_receivable.php"><button type="submit" class="btn btn-info">Atras</button></a>
  </div>
  <div class="col-md-12">
     <div class="panel panel-default">
       <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Abono Cuenta de: <?php echo remove_junk(ucwords($customer['name'])); ?>
        </strong>
       </div>
       <div class="panel-body">
          <form method="post" action="accuonts_report.php?abonar=<?php echo (int)$debts['id'];?>" class="clearfix">
            <div class="row">
              <div class="col-md-6">
                <label for="sales_code" class="control-label">Factura</label>
                <input type="text" class="form-control" name="sales_code" value="<?php echo remove_junk(ucwords($debts['sales_code'])); ?>" readonly> 
              </div>
              <div class="col-md-6">
                <label for="sales_customer" class="control-label">Cliente</label>
                <input type="text" class="form-control" name="sales_customer" value="<?php echo remove_junk(ucwords($customer['cedula'])); ?>" readonly>
              </div>
            </div> 

            <div class="row">
              <div class="col-md-6">
                <label for="totalf" class="control-label">Valor Total</label>
                <input type="text" class="form-control" name="totalf" value="<?php echo remove_junk(ucwords($customer['TotalFactura'])); ?>" readonly>
              </div>
              <div class="col-md-6">
                <label for="abonado" class="control-label">Abonado</label>
                <input type="text" class="form-control" name="abonado" value="<?php echo remove_junk(ucwords($customer['Abono'])); ?>" readonly>
              </div>
            </div> 

            <div class="row">
              <div class="col-md-4">
                <label for="fecha" class="control-label">Fecha Factura</label>
                <input type="text" class="form-control" name="fecha" value="<?php echo remove_junk(ucwords($customer['Fecha'])); ?>" readonly>
              </div>
              <div class="col-md-4">
                <label for="fecha" class="control-label">Fecha Ultimo Abono</label>
                <input type="text" class="form-control" name="fecha" value="<?php echo remove_junk(ucwords($customer['FechaAbono'])); ?>" readonly>
              </div>
              <div class="col-md-4">
                <label for="date" class="control-label">Fecha Actual</label>
                <input type="date" class="form-control datePicker" name="date" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d') ?>">
              </div>
            </div> 

            <div class="row">
              <div class="col-md-4">
                <label for="totalp" class="control-label">Saldo por Pagar</label>
                <input type="text" class="form-control" name="totalp" value="<?php echo remove_junk(ucwords($customer['SaldoporPagar'])); ?>" readonly>
              </div>
              <div class="col-md-4">
                <label for="amount" class="control-label">Abonar</label>
                <input type="text" class="form-control" name="amount" >
              </div>
              <div class="col-md-4">
                <label for="status">Estado</label>
                <select class="form-control" name="status">
                  <option <?php if($customer['status'] === '1') echo 'selected="selected"';?>value="1">Activa</option>
                  <option <?php if($customer['status'] === '0') echo 'selected="selected"';?> value="0">Cancelada</option>
                </select>
              </div>
            </div> 
           
            <div class="form-group clearfix">
                <button type="submit" name="update" class="btn btn-info">Abonar</button>
            </div>
        </form>
        <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th> Id Abono </th>
                    <th> N° Factura</th>
                    <th> Fecha Abono </th>
                    <th> Valor Abonado</th>
                </tr>
                </thead>
            <tbody>
                <?php foreach ($accounts as $account):?>
                <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk($account['id']); ?></td>
                <td><?php echo remove_junk($account['code']); ?></td>
                <td><?php echo remove_junk($account['date']); ?></td>
                <td><?php echo remove_junk($account['amount']); ?></td>
                
                </tr>
                <?php endforeach;?>
            </tbody>
            </table>
       </div>
     </div>
  </div>

 </div>
<?php include_once('layouts/footer.php'); ?>
