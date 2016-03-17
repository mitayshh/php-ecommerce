<?php
    include "controller/ShoppingCartController.class.php";

    $context = new stdClass();
    $controller = new ShoppingCartController($context);
    $controller->process_request();

    $page_title = "Online Store";
    $page_content = "views/cart.view.php";
    include "views/shared/layout.view.php";

    //phpinfo();
?>