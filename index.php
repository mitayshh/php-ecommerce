<?php
    include "controller/ProductController.class.php";

    $context = new stdClass();
    $controller = new ProductController($context);
    $controller->process_request();

    $page_title = "Online Store";
    $page_content = "views/index.view.php";
    include "views/shared/layout.view.php";

    //phpinfo();
?>