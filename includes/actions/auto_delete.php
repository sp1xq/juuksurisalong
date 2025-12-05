<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class auto_delete {

    public static function execute() {
      if (isset($_GET['products_id'])) {
        $pid = (int)$_GET['products_id'];

      $qty = empty($_GET['qty']) ? 1 : (int)$_GET['qty'];

        $_SESSION['cart']->add_cart(
          $_GET['products_id'],
          $_SESSION['cart']->get_quantity(\Product::build_uprid($pid, $attributes))+$qty,
          $attributes);


      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build()
        ->retain_query_except($GLOBALS['parameters']));
    }

  }
