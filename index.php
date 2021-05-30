<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="inventory system">
    <meta name="author" content="julii">

    <title>Inventory System</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="libs/css/index.min.css" rel="stylesheet">
    <link rel="stylesheet" href="libs/css/index.css"/>
    

  </head>
  <body>
  
    <?php echo display_msg($msg); ?>
    <div class="card col-12">
        <!-- Encabezado - Logo -->
        <div class="d-flex justify-content-center">
            <div class="brand_logo_container">
                <img src="libs/images/jk.png" class="brand_logo" alt="Logo">
            </div>
            <!-- Encabezado - Logo -->

            <!-- Formulario-Login  -->
            <form method="post" action="auth.php">
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" name="username" class="form-control input_user" value="" placeholder="Enter user">
                </div>
                <div class="input-group mb-2">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" name="password" class="form-control input_pass" value="" placeholder="Enter password">
                </div>
                <div class="d-flex justify-content-center mt-3 login_container">
                    <button type="submit" name="button" class="btn login_btn">Sign in</button>
                </div>
            </form>
            <!-- Formulario-Login -->
        </div>
    </div>

    <div>
      <footer>&copy; Copyright 2019 Software JK.</footer>
    </div>

  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
 
 