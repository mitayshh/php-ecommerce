<?php
    include "controller/AuthenticationController.class.php";

    $context = new stdClass();
    $controller = new AuthenticationController($context);
    $controller->process_request();

    $page_title = "Online Store";
    $page_content = "views/login.view.php";
    include "views/shared/layout.view.php";
?>