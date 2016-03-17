<?php
include "controller/AdminController.class.php";

$context = new stdClass();
$controller = new AdminController($context);
$controller->process_request();

$page_title = "Online Store";
$page_content = "views/admin.view.php";
include "views/shared/layout.view.php";

//phpinfo();
?>