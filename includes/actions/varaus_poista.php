<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;
use DateTime;
use DatePeriod;
use DateInterval;
use IntlDateFormatter;
  class varaus_poista {

    public static function execute() {
    $formatter = new IntlDateFormatter(LOCAL, IntlDateFormatter::LONG, IntlDateFormatter::NONE,  null, null, null);
    $formatter->setPattern(" cccc dd MMMM y");

   $GLOBALS['hooks']->register_pipeline('loginRequired');
   if (isset($_GET['cal'])) {

    $cal_id = $_GET['cal'];

            $servis_cal_query = $GLOBALS['db']->query( "SELECT es.products_id, es.quantity, es.cal_date, es.cal_end_date, e.cal_name, e.cal_priority FROM webcal_entry e, webcal_entry_servis es WHERE e.cal_id = es.cal_id and e.cal_group_id = es.cal_group_id and e.cal_id =  '" .  (int)$cal_id . "' and e.cal_group_id =  '" . (int)$_SESSION['customer_id'] . "'");
               $pids = '';
          while ($servis_cal = $servis_cal_query->fetch_assoc()) {
               $aeg = tep_get_manufacturer_name('', $servis_cal['cal_priority']);
               $pid = $servis_cal['products_id'];
               $pids .= ' ' . tep_get_products_name($servis_cal['products_id']) . ', ';
               $qty = $servis_cal['quantity'];
               $cal_name = $servis_cal['cal_name'];
               $cal_date = $servis_cal['cal_date'];
               $cal_end_date = $servis_cal['cal_end_date'];
               if($pid !== '0'){
               $_SESSION['cart']->add_cart($pid, $_SESSION['cart']->get_quantity($pid)- $qty , null, false);
               }
           }
               $formatter->setPattern("cccc dd MMMM y ");
               $cal_date_start = ucwords($formatter->format(strtotime($cal_date)));

               $startTime = date('H:i', strtotime($cal_date));
               $endTime =   date('H:i', strtotime($cal_end_date));
               $text = TEXT_BRONN_PERUUTUS . ' ' . $aeg . ' ' . $cal_date_start . ' (' . $startTime . ' - ' . $endTime . ')';

               $GLOBALS['db']->query("UPDATE webcal_entry_servis SET bronn_makstud = 'DelU' WHERE cal_id = '" . (int)$cal_id . "'");
               $GLOBALS['db']->query("UPDATE webcal_entry_user SET cal_status = 'D' WHERE cal_id = '" . (int)$cal_id . "'");

               $_SESSION['bronn']->remove($cal_id);
              // $messageStack->add_session('product_action', $text, 'error');
               $GLOBALS['messageStack']->add_session('product_action', $text, 'warning');

$text_mail = '<strong>' . PERUTUU . '</strong> : ' . WEEK . ' ' . date("W", strtotime($cal_date)) . ucwords($formatter->format(strtotime($cal_date))) . ' ' . TOOPIKKUS . ' <strong>' . ucwords(date('H:i', strtotime($cal_date)) . ' - ' . date('H:i', strtotime($cal_end_date))) . ' </strong>';


html_mail('delete', $cal_id, $text_mail);

$body = PERUTUU . ': ' . $aeg . ' ' . $cal_date_start . ' (' . $startTime . ' - ' . $endTime . ') ' . $pids . ' Kiitos.';
//$to = preg_replace('~^\+3580~', '+358', '+358' . tep_customers_telephone($_SESSION['customer_id']));
$to = tep_customers_telephone($_SESSION['customer_id']);
if(substr($to, 0, 1) !== "t"){
$data = array (
    'From' => STORE_OWNER,
    'To' => $to,
    'Body' => $body,
  );
   sendSMS($data);
   $telefon = $to;
} else {
   $telefon = ' <a href="mailto:' . tep_customers_mail($_SESSION['customer_id']) . '">' . tep_customers_mail($_SESSION['customer_id']) . '</a>';
}

$post = '<b> -- ' . tep_customers_name($_SESSION['customer_id']) . ' </b>';
$post .= $telefon;
$post .= '
<pre>' . PERUTUU . ': ' . $aeg . ' ' . $cal_date_start . ' (' . $startTime . ' - ' . $endTime . ') ' . $pids . '</pre>
';
    sendTelegram($post);

      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build('', ['date' => date('Ymd', strtotime($cal_date))])
        ->retain_query_except(['cal', 'action']));
    }

  }
