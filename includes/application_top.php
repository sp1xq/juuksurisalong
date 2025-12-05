<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());
/*
if(strpos($_SERVER['REMOTE_ADDR'], "65.109.99.209") === 0)
{
  header("location: https://www.google.com/");
   exit();
}
*/
  include 'includes/configure.php';

  require 'includes/system/autoloader.php';
  $class_index = catalog_autoloader::register();

  require 'includes/call/dbi4php.php';

// make a connection to the database... now
  $db = new Database() or die('Unable to connect to database server!');
require_once 'includes/call/classes/Event.php';
require_once 'includes/call/classes/RptEvent.php';
  $hooks = new hooks('shop');
  $OSCOM_Hooks =& $hooks;
  $all_hooks =& $hooks;
  $hooks->register('system');
  foreach ($hooks->generate('startApplication') as $result) {
    if (!isset($result)) {
      continue;
    }

    if (is_string($result)) {
      $result = [ $result ];
    }

    if (is_array($result)) {
      foreach ($result as $path) {
        if (is_string($path ?? null) && file_exists($path)) {
          require $path;
        }
      }
    }
  }
  $formatter = new IntlDateFormatter(LOCAL, IntlDateFormatter::LONG, IntlDateFormatter::NONE,  null, null, null);

if( !isset( $_SERVER['HTTP_USER_AGENT'])){
    $name = "none";
}else{
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
//do something with this information
if( $iPod || $iPhone || $iPad){
    $device = 'mobil';
    $device2 = 'iphone';
} elseif ($Android || $webOS || $berry){
    $device = 'desktop';
    $device2 = 'android';
} else {
    $device = $device2 = 'desktop';
       }
}
$fb = (($device2=='iphone')  ? 'fb://profile/kampaamoparturi.maiste' : (($device2=='android')  ?'fb://page/kampaamoparturi.maiste' : 'https://www.facebook.com/kampaamoparturi.maiste'));
$instagram = (($device=='mobil') ? 'instagram://user?username=evrikamaiste':'https://www.instagram.com/evrikamaiste');

 if (isset($_SESSION['customer_id'])) {
     $cust_data_query = $db->query("SELECT customers_gender FROM customers WHERE customers_id = " . (int)$_SESSION['customer_id'])->fetch_assoc();
     if (empty($cust_data_query['customers_gender'])) {
   $trans_ok = "2";
      } else {
    $trans = array("f" => "2", "m" => "3", "l" => "4", "e" => "2", "0" => "2");
    $trans_ok = strtr((string)$cust_data_query['customers_gender'], $trans);
    }
  } else {
   $trans_ok = "2";
      }
  $current_category_id_modal = $_REQUEST['category_id'] ?? $trans_ok ?? $current_category_id;

  //$delete_bronn_query = $db->query( "SELECT * FROM webcal_entry_servis WHERE cal_end_date <= NOW() AND bronn_makstud  IN ('No', 'NoQ')");
  $delete_bronn_query = $db->query( "SELECT * FROM webcal_entry_servis WHERE cal_end_date <= NOW()");
  if (mysqli_num_rows($delete_bronn_query)) {
   while ($delete_bronn = $delete_bronn_query->fetch_assoc()) {
          $cal_bronn_id = $delete_bronn['cal_id'];
          $customer_bronn = $delete_bronn['cal_group_id'];
          $produc_bronn = $delete_bronn['products_id'];
       //if(($produc_bronn !== '0') || ($delete_bronn['cal_id'] !== 'Yes')){
       if($produc_bronn !== '0'){
          $delete_bronn_query2 = $db->query("SELECT customers_basket_quantity FROM customers_basket WHERE customers_id = '" . (int)$customer_bronn . "' AND products_id = '" . (int)$produc_bronn . "'");
          if (mysqli_num_rows($delete_bronn_query2)) {
          $delete_bronn2 = $delete_bronn_query->fetch_assoc();
          $basket_quantity = $delete_bronn2['customers_basket_quantity'] ?? null;
          $quantity = ($basket_quantity - $delete_bronn['quantity']);
       if(1 < $basket_quantity ) {
            $db->query("UPDATE customers_basket SET customers_basket_quantity = '" . (int)$quantity . "' WHERE customers_id = " . (int)$customer_bronn . " AND products_id = '" . (int)$produc_bronn . "'");
          } else {
            $db->query("DELETE FROM customers_basket WHERE products_id = '" . (int)$produc_bronn . "' AND customers_id = '" . (int)$customer_bronn . "'");
          }
       }
    }
          $db->query("DELETE FROM customers_basket_bronn WHERE cal_id = '" . (int)$cal_bronn_id . "' AND customers_id = '" . (int)$customer_bronn.  "'");
          $db->query("UPDATE webcal_entry_servis SET bronn_makstud = 'DelS' WHERE cal_id = '" . (int)$cal_bronn_id . "'");
          $db->query("UPDATE webcal_entry_user SET cal_status = 'S'  WHERE cal_id = '" . (int)$cal_bronn_id . "'");
    }
  }
      function isOpen($getTrackCode){
            $now =$this->getDate();
            try{
                $query = "UPDATE ".$this->dbhTable." SET openDate=?, isOpen=? WHERE trackCode=? AND isOpen='no'";

                $stmt = $this->connect()->prepare($query);
                $stmt->execute([$now, 'yes', $getTrackCode]);

                echo '<p style="background:green; color:white; padding: 5px 5px;">DATABASE UPDATED'.$getTrackCode.'</p>';
                echo $query;
            }catch(PDOException $e){
                echo $query.'Error '.$e->getMessage();
            }
        }
if(isset($_GET['t'])) {
$db->query("UPDATE zsms_joulud2024 SET openDate = NOW(), isOpen='yes' WHERE trackCode = '" . $_GET['t'] . "' AND isOpen='no'");
}
/*
  if(isset($_SESSION['customer_id'])) {
    if($_SESSION['cart']->count_contents() != tep_count_customers_basket()){
       $_SESSION['cart'] = new shoppingCart();
       $_SESSION['cart']->restore_contents();
     }
    if($_SESSION['bronn']->count_contents() != tep_count_customers_bronn()){
       $_SESSION['bronn'] = new bronnCart();
       $_SESSION['bronn']->restore_contents_bronn();
     }
 }

if(isset($_GET['e'])) {
Href::redirect($Linker->build('index.php')->set_parameter('em', $_GET['e']));
}
if(isset($_GET['i'])) {
Href::redirect($Linker->build('index.php')->set_parameter('tl', $_GET['i']));
}

 */
if(isset($_GET['g'])) {
Href::redirect($Linker->build('index.php')->set_parameter('tlf', $_GET['g']));
}
