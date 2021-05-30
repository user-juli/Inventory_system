<?php
  $page_title = 'Venta diaria';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>

<?php
 $year  = date('Y');
 $month = date('m');
 $day = date('d');
 //$sales = dailySales($year,$month,$day );
?>

<?php 
  require_once('includes/load.php');	
 
if(isset($_POST['create_pdf'])){
  require_once('tcpdf/tcpdf.php');
  
  $start_date   = $_POST['start-date'];
  $end_date     = $_POST['end-date'];

  $usuario = "SELECT s.code,SUM(ds.qy) as cantidad, DATE_FORMAT(s.date, '{$year}-{$month}-{$day}') AS date,p.name, ds.total AS total_saleing_price FROM sales s,details_sales ds,products p WHERE ds.product_id=p.code AND ds.sale_id=s.code AND DATE_FORMAT(s.date, '%Y-%m-%d' ) = '{$year}-{$month}-{$day}' GROUP BY ds.product_id"; 
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
          <th> Factura </th>
          <th> Producto </th>
          <th> Cantidad vendida</th>
          <th> Total </th>
          <th> Fecha </th>
          </tr>
        </thead>
	';
 
	while ($user=$usuarios->fetch_assoc())  { 
			$content .= '
		    <tr>
            <td>'.$user['code'].'</td>    
            <td>'.$user['name'].'</td>
            <td>'.$user['cantidad'].'</td>
            <td>'.$user['total_saleing_price'].'</td>
            <td>'.$user['date'].'</td>
            
        </tr>
	    ';
	}
 
  $content .= '<tfoot>
    <tr class="text-right">
    <td colspan="3"></td>
    <td colspan="1"> Total </td>
    <td> $ ';  echo number_format(@total_price($usuarios)[0], 2);
   $content .= ' </td>
    </tr>
    <tr class="text-right">
      <td colspan="3"></td>
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
            <span>Venta diaria</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Factura </th>
                <th> Producto </th>
                <th class="text-center" style="width: 15%;"> Cantidad vendida</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Fecha </th>
             </tr>
            </thead>
           <tbody>
           
             <?php  
                $consulta = "SELECT s.code,SUM(ds.qy) as cantidad, DATE_FORMAT(s.date, '{$year}-{$month}-{$day}') AS date,p.name, ds.total AS total_saleing_price FROM sales s,details_sales ds,products p WHERE ds.product_id=p.code AND ds.sale_id=s.code AND DATE_FORMAT(s.date, '%Y-%m-%d' ) = '{$year}-{$month}-{$day}' GROUP BY ds.product_id"; 
                $sales = $db->query($consulta) or die ("Error en consulta:".mysql_error()); 
                while ($sale = mysqli_fetch_array($sales)){ 
             ?> 
              
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['code']); ?></td>
               <td><?php echo remove_junk($sale['name']); ?></td>
               <td class="text-center"><?php echo (int)$sale['cantidad']; ?></td>
               <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
               <td class="text-center"><?php echo date("d/m/Y", strtotime ($sale['date'])); ?></td>
             </tr>
             <?php }?>
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
