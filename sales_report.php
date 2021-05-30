<?php
$page_title = 'Reporte de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>

<?php 
  require_once('includes/load.php');	
 
if(isset($_POST['create_pdf'])){
  require_once('tcpdf/tcpdf.php');
  
  $start_date   = $_POST['start-date'];
  $end_date     = $_POST['end-date'];

  $usuario = "SELECT s.date, p.name,p.sale_price,p.buy_price,COUNT(ds.product_id) AS total_records,SUM(ds.qy) AS total_sales,SUM(p.sale_price * ds.qy) AS total_saleing_price,SUM(p.buy_price * ds.qy) AS total_buying_price FROM products p, sales s, details_sales ds WHERE ds.sale_id=s.code AND ds.product_id=p.code AND s.date BETWEEN '$start_date' AND '$end_date' GROUP BY DATE(s.date),p.name ORDER BY DATE(s.date) DESC" ;
  $usuarios = $db->query($usuario);
 
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
 
	$pdf->SetCreator('Ventas Mensuales');
	$pdf->SetAuthor('Software JK');
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
          <th>Fecha</th>
          <th>Descripción</th>
          <th>Precio de compra</th>
          <th>Precio de venta</th>
          <th>Cantidad total</th>
          <th>TOTAL</th>
          </tr>
        </thead>
	';
 
	while ($user=$usuarios->fetch_assoc())  { 
			$content .= '
		    <tr>
            <td>'.$user['date'].'</td>    
            <td>'.$user['name'].'</td>
            <td>'.$user['buy_price'].'</td>
            <td>'.$user['sale_price'].'</td>
            <td>'.$user['total_sales'].'</td>
            <td>'.$user['total_saleing_price'].'</td>
        </tr>
	    ';
	}
 
  $content .= '<tfoot>
    <tr class="text-right">
    <td colspan="4"></td>
    <td colspan="1"> Total </td>
    <td> $ ';  echo number_format(@total_price($usuarios)[0], 2);
   $content .= ' </td>
    </tr>
    <tr class="text-right">
      <td colspan="4"></td>
      <td colspan="1">Utilidad</td>
      <td>$';  echo number_format(@total_price($usuarios)[1], 2);
      $content .= ' </td>
    </tr>
    </tfoot>
    ';

	$content .= '</table>';
 
 
	$pdf->writeHTML($content, true, 0, true, 0);
 
  $pdf->lastPage();
  ob_end_clean();
	$pdf->output('report.pdf', 'I');
}

?>

<?php
  if(isset($_POST['create_pdf'])){
    redirect('sale_report_process.php');
  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">

      </div>
      <div class="panel-body">
          <form class="clearfix" method="POST" action="sales_report.php">
            <div class="form-group">
              <label class="form-label">Rango de fechas</label>
                <div class="input-group">
                  <input type="text" class="datepicker form-control" name="start-date" placeholder="From">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" placeholder="To">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Generar Reporte</button>
                <input type="submit" name="create_pdf" class="btn btn-danger pull-right" value="Generar PDF">
            </div>
          </form>
      </div>

    </div>
  </div>

</div>
<?php include_once('layouts/footer.php'); ?>
