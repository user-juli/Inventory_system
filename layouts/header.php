<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="julii">
    <meta name="description" content="inventory system">

    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Inventory System";?>
    </title>
	
    <!-- Custom css for this template-->
    <link href="libs/css/index.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="libs/css/main.css" />
    <link rel="stylesheet" href="libs/css/style.css" />
    
  </head>

  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <div class="page-wrapper chiller-theme toggled">
      <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
        <i class="fas fa-bars"></i>
      </a>
      <nav id="sidebar" class="sidebar-wrapper">
        <div class="sidebar-content">
          <div class="sidebar-brand">
            <a href="#">Inventory System</a>
            <div id="close-sidebar">
              <i class="fas fa-times"></i>
            </div>
          </div>
          <div class="sidebar-header">
            <div class="user-pic">
              <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-responsive img-rounded">
            </div>
            <div class="user-info">
              <span class="user-name">User:
                <strong><?php echo remove_junk(ucfirst($user['name'])); ?></strong>
              </span>
              <span>
                <a href="edit_account.php" title="edit account"><i class="fas fa-cog"></i></a>
                <a href="logout.php"> <i class="fas fa-power-off"></i></a>
              </span>
            </div>
          </div>

          <div class="sidebar-search">
            <div class="input-group-append">
              <span>
                <?php date_default_timezone_set('America/Bogota');?>
                <strong><?php  echo date("d/m/Y  g:i a");?></strong>
              </span>
            </div>
          </div>

          <div class="sidebar-menu">
            <?php if($user['user_level'] === '1'): ?>
              <!-- admin menu -->
              <?php include_once('admin_menu.php');?>

              <?php elseif($user['user_level'] === '2'): ?>
              <!-- Special user -->
              <?php include_once('special_menu.php'); ?>
              
              <?php elseif($user['user_level'] === '3'): ?>
              <!-- User menu -->
              <?php include_once('user_menu.php');?>

            <?php endif;?>
          </div>
          <!-- sidebar-menu  -->
        </div>
        <!-- sidebar-content  -->
        
      </nav>
  <?php endif;?>

      
    <!-- sidebar-wrapper  -->
    <main class="page-content">
      <div class="container">

  