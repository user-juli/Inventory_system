<?php
$page_title = 'Cuentas por Cobrar';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
   $debts = accounts_debts();
?>

<?php 
	require_once('includes/load.php');	
	$usuarios = accounts_debts();
 
if(isset($_POST['create_pdf'])){
	require_once('tcpdf/tcpdf.php');
 
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
 
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Miguel Caro');
	$pdf->SetTitle($_POST['reporte_name']);
 
	$pdf->setPrintHeader(false); 
	$pdf->setPrintFooter(false);
	$pdf->SetMargins(20, 20, 20, false); 
	$pdf->SetAutoPageBreak(true, 20); 
	$pdf->SetFont('Helvetica', '', 10);
	$pdf->addPage();
 
	$content = '';
 
	$content .= '
		<div class="row">
        	<div class="col-md-12">
            	<h1 style="text-align:center;">'.$_POST['reporte_name'].'</h1>
 
      <table border="1" cellpadding="5">
        <thead>
          <tr>
            <th> N° Factura </th>
            <th> Cedula</th>
            <th> Nombres </th>
            <th> Direccion</th>
            <th> Telefono </th>
            <th> Total Venta </th>
            <th> Abonado </th>
            <th> Saldo por Pagar </th>
            <th> Deuda </th>
          </tr>
        </thead>
	';
 
	while ($user=$usuarios->fetch_array())  { 
			if($user['estado']=='1'){  $color= '#f5f5f5'; }else{ $color= '#fbb2b2'; }
	$content .= '
		<tr bgcolor="'.$color.'">
            <td>'.$user['code'].'</td>
            <td>'.$user['id'].'</td>
            <td>'.$user['name'].'</td>
            <td>'.$user['address'].'</td>
            <td>'.$user['phone'].'</td>
            <td>'.$user['TotalFactura'].'</td>
            <td>'.$user['Abono'].'</td>
            <td>S/. '.$user['Saldoporpagar'].'</td>
            <td>'.$user['estado'].'</td>
        </tr>
	';
	}
 
	$content .= '</table>';
 
	$content .= '
		<div class="row padding">
        	<div class="col-md-12" style="text-align:center;">
            	<span>Pdf Creator </span><a href="http://www.redecodifica.com">By Miguel Angel</a>
            </div>
        </div>
 
	';
 
	$pdf->writeHTML($content, true, 0, true, 0);
 
	$pdf->lastPage();
	$pdf->output('report.pdf', 'I');
}
 
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
                <span>Cuentas por Cobrar</span>
            </strong>
            </div>
            <div class="panel-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th> N° Factura </th>
                    <th> Cedula</th>
                    <th> Nombres </th>
                    <th> Direccion</th>
                    <th> Telefono </th>
                    <th> Total Venta </th>
                    <th> Abonado </th>
                    <th> Saldo por Pagar </th>
                    <th> Deuda </th>
                    <th> Acciones </th>
                </tr>
                </thead>
              <tbody>
                <?php foreach ($debts as $debt):?>
                <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk($debt['code']); ?></td>
                <td><?php echo remove_junk($debt['id']); ?></td>
                <td><?php echo remove_junk($debt['name']); ?></td>
                <td><?php echo remove_junk($debt['address']); ?></td>
                <td><?php echo remove_junk($debt['phone']); ?></td>
                <td><?php echo remove_junk($debt['TotalFactura']); ?></td>
                <td><?php echo remove_junk($debt['Abono']); ?></td>
                <td><?php echo remove_junk($debt['SaldoporPagar']); ?></td>
                <td class="text-center">
                <?php if($debt['estado'] === '1'): ?>
                  <span class="label label-success"><?php echo "Activa"; ?></span>
                <?php else: ?>
                  <span class="label label-danger"><?php echo "Cancelada"; ?></span>
                <?php endif;?>
                </td>
                <td>
                <div class="btn-group">
                  <a href="accuonts_report.php?abonar=<?php echo (int)$debt['abonar'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Abonar">
                    <i class="glyphicon glyphicon-pencil"></i> 
                  </a>
                </div>
                </td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
              <div class="col-md-12">
              	<form method="post">
                	<input type="hidden" name="reporte_name" value="<?php echo $h1; ?>">
                	<input type="submit" name="create_pdf" class="btn btn-danger pull-right" value="Generar PDF">
                </form>
              </div>
          </div>
        </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
