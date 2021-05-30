<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level,store_id FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level,store_id FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name, s.name as store ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ";
      $sql .="LEFT JOIN stores s ";
      $sql .="ON s.id=u.store_id ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] = '0'):
           $session->msg('d','Este usuario esta inactivo!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('home.php', false);
        endif;

     }

   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.code,p.name,p.stock,p.buy_price,p.sale_price,p.media_id,p.date,c.name AS categorie,";
    $sql  .=" v.name AS provider, m.file_name AS image, s.name AS store";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" LEFT JOIN providers v ON v.id = p.provider_id";
    $sql  .=" LEFT JOIN stores s ON s.id = p.store_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

  function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   } 
   
  
  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Finding all customers info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_customer_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM customers ";
    $sql .= " WHERE id ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET stock=stock -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name as produ, SUM(ds.qy) as totalQty ";
   $sql .= " FROM details_sales ds, sales s, products p ";
   $sql .= " WHERE ds.sale_id=s.code AND ds.product_id=p.code ";
   $sql .= " GROUP BY ds.product_id";
   $sql .= " ORDER BY SUM(ds.qy) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }

 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_copy_product($limit){
  global $db;
  $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
  $sql .= " GROUP BY s.product_id";
  $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
  return $db->query($sql);
}

 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.code as Factura,ds.qy,s.total_sale,s.date,p.name as Producto, c.name as cliente,ds.total";
   $sql .= " FROM sales s,details_sales ds, products p,customers c";
   $sql .= " WHERE ds.sale_id=s.code AND ds.product_id = p.code AND s.customer_id=c.id";
   $sql .= " ORDER BY s.id DESC";
   return find_by_sql($sql);
 }

 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_copy_sale(){
  global $db;
  $sql  = "SELECT s.code,p.code,p.name,ds.qy,p.sale_price,ds.total,s.date";
  $sql .= " FROM products p, sales s, details_sales ds ";
  $sql .= "WHERE ds.sale_id=s.code AND ds.product_id=p.code" ;
  $sql .= " ORDER BY s.date DESC";
  return find_by_sql($sql);
}
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.code as factu,p.code,p.name as produ,ds.qy,p.sale_price,ds.total,s.date";
  $sql .= " FROM products p, sales s, details_sales ds ";
  $sql .= "WHERE ds.sale_id=s.code AND ds.product_id=p.code" ;
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(ds.product_id) AS total_records,";
  $sql .= "SUM(ds.qy) AS total_sales,";
  $sql .= "SUM(p.sale_price * ds.qy) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * ds.qy) AS total_buying_price ";
  $sql .= " FROM products p, sales s, details_sales ds ";
  $sql .= "WHERE ds.sale_id=s.code AND ds.product_id=p.code " ;
  $sql .= " AND s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}

/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year,$month){
  global $db;
  $sql  = "SELECT ds.qy,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= " ds.total AS total_saleing_price";
  $sql .= " FROM sales s,details_sales ds,products p ";
  $sql .= " WHERE ds.product_id = p.code AND ds.sale_id=s.code ";
  $sql .= " AND DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),ds.product_id";
  return find_by_sql($sql);
}

  /*--------------------------------------------------------------*/
  /* Find all customers
  /*--------------------------------------------------------------*/
  function find_all_customer(){
    global $db;
    $results = array();
    $sql = "SELECT * from customers ";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Find all providers
  /*--------------------------------------------------------------*/
  function find_all_provider(){
    global $db;
    $results = array();
    $sql = "SELECT * from providers ";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

  function find_product_by_name($product_name){
    global $db;
    $p_name = remove_junk($db->escape($product_name));
    $sql = "SELECT code,name,stock,buy_price,sale_price FROM products WHERE name like '%$p_name%' LIMIT 5";
    $result = find_by_sql($sql);
    return $result;
  }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

  function find_customer_by_title($customer_name){
    global $db;
    $c_name = remove_junk($db->escape($customer_name));
    $sql = "SELECT * FROM customers WHERE name like '%$c_name%' OR last_name like '%$c_name%' LIMIT 5";
    $result = find_by_sql($sql);
    return $result;
  }

  function accounts_debts(){
    global $db;
    $sql = "SELECT s.code,c.id,c.name,c.address,c.phone,MIN(d.status) as estado,a.id as abonar,s.total_sale as TotalFactura, SUM(a.amount) as Abono, MAX(s.total_sale) - SUM(a.amount) as SaldoporPagar FROM customers c, amounts a, debts d, sales s WHERE a.id=d.amount_id AND a.sales_customer=s.customer_id AND a.sales_code=s.code AND s.customer_id=c.id  GROUP BY c.id";
    return find_by_sql($sql);
  }

  
 /*--------------------------------------------------------------*/
 /* Function for abonar deudas clientes
 /*--------------------------------------------------------------*/
function debts_accounts($customer){
  global $db;
  $sql = $db->query("SELECT s.code,c.id as cedula,c.name,c.phone,Min(a.date) as Fecha,MAX(a.date) as FechaAbono,s.total_sale as TotalFactura, SUM(a.amount) as Abono, MAX(s.total_sale) - SUM(a.amount) as SaldoporPagar, d.status FROM customers c,amounts a, debts d, sales s WHERE a.id=d.amount_id AND a.sales_customer=s.customer_id AND a.sales_code=s.code AND s.customer_id=c.id AND s.code='{$db->escape($customer)}' LIMIT 1 ");
    if($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
}
  
function history_debts($customer){
  global $db;
 $sql  = "SELECT a.id, code, a.date, amount FROM amounts a, sales s WHERE a.sales_code=s.code AND s.code= '{$db->escape($customer)}' ";
  return find_by_sql($sql);
 }

 function find_edit_sale($sale){
  global $db;
 $sql  = "SELECT * FROM sales WHERE code= '{$db->escape($sale)}' ";
  return find_by_sql($sql);
 }

 function find_edit_Allsale($sale){
  global $db;
 $sql  = "SELECT ds.id,ds.sale_id,ds.product_id,ds.qy,ds.total FROM sales s, details_sales ds, products p WHERE ds.sale_id=s.code AND ds.product_id=p.code AND s.code= '{$db->escape($sale)}' ";
  return find_by_sql($sql);
 }

 function count_debts(){
  global $db;
  $sql    = "SELECT * FROM amounts, debts, sales WHERE sales.code=amounts.sales_code AND amounts.id=debts.amount_id AND status=1 GROUP BY sales.customer_id";
  $result = $db->query($sql);
  return($db->fetch_assoc($result));

 }

 /*--------------------------------------------------------------*/
  /* Function for Finding all sales code
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

  function find_sale_by_title($fact_name){
    global $db;
    $s_code = remove_junk($db->escape($fact_name));
    $sql = "SELECT code FROM sales WHERE code like '%$s_code%' LIMIT 5";
    $result = find_by_sql($sql);
    return $result;
  } 
 
 /*--------------------------------------------------------------*/
 /* Function for Finding all product info by product title
 /* Request coming from ajax.php
 /*--------------------------------------------------------------*/
 function find_all_sale_info_by_title($title){
   global $db;
   $sql  = "SELECT s.code as fact,c.name as scliente, s.user_name as vendedor,s.date as fecha, ds.qy,ds.total,p.code as prod,p.name as nprod,p.sale_price, w.description";
   $sql .= " FROM sales s,details_sales ds,products p,way_to_pay w,customers c ";
   $sql .= "WHERE ds.sale_id=s.code AND ds.product_id=p.code AND s.customer_id=c.id AND s.pay_id=w.id ";
   $sql .= "AND s.code ='{$title}'";
   return find_by_sql($sql);
 }

/*--------------------------------------------------------------*/
  /* Find all returns
  /*--------------------------------------------------------------*/
  function find_all_return(){
    global $db;
    $results = array();
    $sql = "SELECT * from returns ";
    return find_by_sql($sql);
  }

?>


