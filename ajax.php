<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
 // Auto suggetion 
    $html = '';
   if(isset($_POST['product_name']) && strlen($_POST['product_name']))
   {
     $products = find_product_by_title($_POST['product_name']); 

      if($products){
        foreach ($products as $product):
          $html .= "<li class=\"list-group-item\">";
          $html .= $product['name'];
          $html .= "</li>";
        endforeach;
        
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'No encontrado';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>

<?php
 // find all product

  if(isset($_POST['p_name']) && strlen($_POST['p_name']))
  {
    $product_title = remove_junk($db->escape($_POST['p_name']));
    if($results = find_all_product_info_by_title($product_title)){
        foreach ($results as $result) {

          $html .= "<tr class=\"fields\">";
          $html .= "<td id=\"s_name\">".$result['name']."</td>";
          $html .= "<input type=\"hidden\" class=\"form-control\" name=\"p_id[]\" value=\"{$result['code']}\">";
          $html .= "</td>";
          $html .= "<td>";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"s_qy[]\" id=\"s_qy\" value=\"1\">";
          $html .= "</td>";
          $html .= "<td>";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"s_price\" id=\"s_price\" value=\"{$result['sale_price']}\" >";
          $html .= "</td>";
          $html .= "<td>";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"s_iva\" value=\"19\" >";
          $html .= "</td>";
          $html .= "<td>";
          $html .= "<input type=\"text\" class=\"form-control s_total\" name=\"s_total[]\" id=\"s_total\">";
          $html .= "</td>";
          $html .= "<td>";
          $html .= "<input type=\"date\" class=\"form-control datePicker fecha\" name=\"s_date\" data-date-format=\"yyyy-mm-dd\">";
          $html .= "</td>";
          $html .= "<td>";
          $html .= "<button type=\"button\" class=\"btn btn-danger eliminar\" title=\"Eliminar\" data-toggle=\"tooltip\" ><span class=\"glyphicon glyphicon-remove\"></span></button>";
          $html .= "</td>";
          $html .= "</tr>";
           
        }
    } else {
        $html .= "<li class=\"list-group-item\">";
        $html .= "El producto no se encuentra registrado en la base de datos";
        $html .= "</li>";
        
    }

    echo json_encode($html);
  }

?>

<?php
 // Auto suggetion
    $html = '';
   if(isset($_POST['fact_name']) && strlen($_POST['fact_name']))
   {
     $sales = find_sale_by_title($_POST['fact_name']);

      if($sales){
        foreach ($sales as $sale):
          $html .= "<li class=\"list-group-item\">";
          $html .= $sale['code'];
          $html .= "</li>";
        endforeach;
        
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'No encontrado';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>

<?php
 // find all sale

  if(isset($_POST['f_name']) && strlen($_POST['f_name']))
  {
    $sale_title = remove_junk($db->escape($_POST['f_name']));
    if($results = find_all_sale_info_by_title($sale_title)){
      foreach ($results as $result) {
    
        $html .= "<tr>";
        $html .= "<td>";
        $html .= "<input type=\"text\" class=\"form-control sale\" name=\"s_id[]\" value=\"{$result['fact']}\" readonly>";
        $html .= "</td>";
        $html .= "<td id=\"s_name\">".$result['nprod']."</td>";
        $html .= "<input type=\"hidden\" class=\"form-control pd\" name=\"p_id[]\" value=\"{$result['prod']}\">";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<input type=\"text\" class=\"form-control qy\" name=\"qy[]\" value=\"{$result['qy']}\" readonly>";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<input type=\"text\" class=\"form-control\" value=\"{$result['sale_price']}\" readonly>";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<input type=\"text\" class=\"form-control tot\" name=\"tot[]\" value=\"{$result['total']}\"  readonly>";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<input type=\"text\" class=\"form-control\" value=\"{$result['fecha']}\" readonly>";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<button type=\"button\" class=\"btn btn-danger devolver\" title=\"Devolucion\" data-toggle=\"tooltip\" ><span class=\"glyphicon glyphicon-refresh\"></span></button>";
        $html .= "</td>";
        $html .= "</tr>";  
         
      }
    } else {
        $html .= "<li class=\"list-group-item\">";
        $html .= "La factura no se encuentra registrada en la base de datos";
        $html .= "</li>";
        
    }

    echo json_encode($html);
  }

?>

