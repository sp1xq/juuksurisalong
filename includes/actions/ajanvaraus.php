<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
  namespace Phoenix\Actions;
use DateTime;
use DatePeriod;
use DateInterval;
use IntlDateFormatter;
  class ajanvaraus {

    public static function execute() {
    $formatter = new IntlDateFormatter(LOCAL, IntlDateFormatter::LONG, IntlDateFormatter::NONE,  null, null, null);
    $formatter->setPattern(" cccc dd MMMM y");

    if (!isset($_SESSION['customer_id'])) {
      //   $_SESSION['varaus'] = 'on';
         $GLOBALS['hooks']->register_pipeline('loginRequired');
      }

    if (isset($_GET['date']) && ($_SESSION['servis']->count_contents() > 0)) {

     $maksumus = $_SESSION['servis']->show_total();

     $produ_id = '';
     $cal_date = $_GET['date'];
     $manufacturers_id = $_GET['manufacturers_id'];
     $cal_id = add_cart_aeg_admin($produ_id, $manufacturers_id , $_SESSION['customer_id'], $cal_date, $_GET['hour'], $_GET['minute'], $_GET['defusers'], $maksumus);

         $start_time = ucwords(date("Y-m-d", strtotime($cal_date))) . ' ' . $_GET['hour'] . ':' . $_GET['minute'];
         $minut = ($manufacturers_id * 15);
         $time = new DateTime($start_time);
         $time->add(new DateInterval('PT' . $minut . 'M'));
         $end_time = $time->format('Y-m-d H:i');
         $_SESSION['bronn']->add_cart($cal_id, '1',$manufacturers_id, '', $start_time, $end_time);


         $pids = '';
     foreach ($_SESSION['servis']->get_products() as $product) {

       $pidsid = $product->get('id');
       $tky = $product->get('quantity');

       $GLOBALS['db']->query("INSERT INTO webcal_entry_servis (cal_id, cal_group_id, products_id, quantity, cal_date, cal_end_date) values ('" . $cal_id . "', '" . (int)$_SESSION['customer_id'] . "', '" . $pidsid . "', '" . $tky . "', '" . $start_time . "', '" . $end_time . "')");

     $pids .= ' ' . $product->get('name') . ', ';
     $_SESSION['servis']->remove($pidsid);
    //   $_SESSION['servis']->add_cart($pidsid, $_SESSION['servis']->get_quantity($pidsid)- $tky , null, false);
    }

 html_mail('add', '', '');

$body = TEXT_BRONN . ': ' . tep_get_manufacturer_name('', $manufacturers_id) . ' ' . ucwords($formatter->format(strtotime($start_time))) . ' (' . ucwords(date('H:i', strtotime($start_time))) . ' - ' . ucwords(date('H:i', strtotime($end_time))) . ') ' . $pids . ' Kiitos.';
$to = tep_customers_telephone($_SESSION['customer_id']);

 if(substr($to, 0, 1) !== "t"){
   $telefon = '<a href="tel:' . $to . '">' . $to . '</a>';
   $data = array (
    'From' => STORE_OWNER,
    'To' => $to,
    'Body' => $body,
  );
   sendSMS($data);
 } else {
   $telefon = ' <a href="mailto:' . tep_customers_mail($_SESSION['customer_id']) . '">' . tep_customers_mail($_SESSION['customer_id']) . '</a>';
 }

$post = '<b> # ' . tep_customers_name($_SESSION['customer_id']) . ' </b>';
$post .= $telefon;
$post .= '
<pre>' . $body . '</pre>
';
    sendTelegram($post);

$GLOBALS['messageStack']->add_session(
          'product_action',
          sprintf(PRODUCT_ADDED, $body),
          'success');

    $_SESSION['cart']->restore_contents();
    $_SESSION['servis']->restore_contents_servis();
    $_SESSION['bronn']->restore_contents_bronn();
  //    $messageStack->add_session('product_action', $text, 'success');
      }
      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build('shopping_cart.php'));
      /*
      $parameters = array('action', 'sort', 'cPath', 'pid', 'manufacturers_id', 'hour', 'minute', 'defusers', 'date');
     // tep_redirect(tep_href_link('shopping_cart.php', tep_get_all_get_params($parameters)));
       \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['goto'])
        ->retain_query_except($GLOBALS['parameters']));
       */
    }
  }
