<?php
  $page_title = 'Ventas mensuales';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
 $year = date('Y');
 $month = date('m');
 $sales = monthlySales($year,$month );
?>

<?php 
  require_once('includes/load.php');	
  $year = date('Y');
  $month = date('m');
  $usuario = "SELECT ds.qy, DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name, ds.total AS total_saleing_price FROM sales s,details_sales ds,products p WHERE ds.product_id = p.code AND ds.sale_id=s.code AND DATE_FORMAT(s.date, '%Y-%m' ) =  '$year-$month' GROUP BY DATE_FORMAT( s.date, '%e' ),ds.product_id" ;
  $usuarios = $db->query($usuario);
 
if(isset($_POST['create_pdf'])){
	require_once('tcpdf/tcpdf.php');
 
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
 
	$pdf->SetCreator('Ventas Mensuales');
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
            	<h1 style="text-align:center;">Ventas Mensuales</h1>
 
      <table border="1" cellpadding="5">
        <thead>
          <tr>
          <th>Descripción</th>
          <th>Cantidad Vendida</th>
          <th>Total</th>
          <th>Fecha</th>
          </tr>
        </thead>
	';
 
	while ($user=$usuarios->fetch_assoc())  { 
			$content .= '
		    <tr>
            <td>'.$user['name'].'</td>
            <td>'.$user['qy'].'</td>
            <td>'.$user['total_saleing_price'].'</td>
            <td>'.$user['date'].'</td>
        </tr>
	    ';
	}
 
	$content .= '</table>';
 
 
	$pdf->writeHTML($content, true, 0, true, 0);
 
  $pdf->lastPage();
  ob_end_clean();
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
            <span>Ventas mensuales</span>
          </strong>
          <div class="pull-right">
            <a href="report.php" class="btn btn-primary">Agregar venta</a>
          </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Descripción </th>
                <th class="text-center" style="width: 15%;"> Cantidad vendidas</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Fecha </th>
             </tr>
            </thead>
           <tbody>
           <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['name']); ?></td>
               <td class="text-center"><?php echo (int)$sale['qy']; ?></td>
               <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
               <td class="text-center"><?php echo date("d/m/Y", strtotime ($sale['date'])); ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
          <form method="post">
            <input type="submit" name="create_pdf" class="btn btn-danger pull-right" value="Generar PDF">
          </form>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
