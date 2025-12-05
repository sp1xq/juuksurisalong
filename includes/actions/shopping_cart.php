<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class shopping_cart {

    public static function execute() {

          if (isset($_GET['products_id'])) {
           $pid = (int)$_GET['products_id'];

          if ((isset($_GET['kas'])) && ($_GET['kas'] == 'add')) {
             $_SESSION['cart']->add_cart($pid, $_SESSION['cart']->get_quantity($pid)+1);
          if (isset($_GET['ser'])){
             $_SESSION['servis']->add_cart($pid, $_SESSION['servis']->get_quantity($pid)+1);
           }
           $GLOBALS['messageStack']->add_session(
                    'product_action',
                     sprintf(PRODUCT_ADDED, \Product::fetch_name($pid)),
                    'success');

        } elseif ((isset($_GET['kas'])) && ($_GET['kas'] == 'remove')) {

             $_SESSION['cart']->add_cart($pid, $_SESSION['cart']->get_quantity($pid)-1);
          if (isset($_GET['ser'])){
             $_SESSION['servis']->add_cart($pid, $_SESSION['servis']->get_quantity($pid)-1);
           }

            $GLOBALS['messageStack']->add_session('product_action', sprintf(PRODUCT_UPDATE, \Product::fetch_name($pid)), 'warning');

        } elseif ((isset($_GET['kas'])) && ($_GET['kas'] == 'delete')) {
           $qty = $_GET['qty'] ?? '0';

              $_SESSION['cart']->add_cart($pid, $_SESSION['cart']->get_quantity($pid)-$qty);
          if (isset($_GET['ser'])){
              $_SESSION['servis']->add_cart($pid, $_SESSION['servis']->get_quantity($pid)-$qty);
           }

        $GLOBALS['messageStack']->add_session('product_action', sprintf(PRODUCT_REMOVED, \Product::fetch_name($pid)), 'warning');


        } elseif ((isset($_GET['kas'])) && ($_GET['kas'] == 'adds')) {



        }
    }





$param = ['action', 'cPath', 'sort', 'pid', 'ser', 'cart_quantity', 'kas', 'products_id', 'modal'];
if (isset($_GET['ser'])) {

      $modal = (isset($_GET['modal']) ? 'modal=' . $_GET['modal'] : '');

     // tep_redirect(tep_href_link('shopping_cart.php', $modal));

      \Href::redirect(\Guarantor::ensure_global('Linker')
        -> build('shopping_cart.php')
        ->retain_query_except($GLOBALS['parameters']));
 } else {

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['goto'])
        ->retain_query_except($GLOBALS['parameters']));
      }
    }

  }





