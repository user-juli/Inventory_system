<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $city = find_by_id('cities',(int)$_GET['id']);
  if(!$city){
    $session->msg("d","ID de la ciudad falta.");
    redirect('city.php');
  }
?>
<?php
  $delete_id = delete_by_id('cities',(int)$city['id']);
  if($delete_id){
      $session->msg("s","Ciudad eliminada");
      redirect('city.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('city.php');
  }
?>
