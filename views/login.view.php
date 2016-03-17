<?php
    require "shared/show_error_messages.view.php";
?>

<section id="login">
    <h2>Login</h2>
    <?php include "shared/login_form.view.php"; ?>
    <?php if ($context->errors) { ?>
        <div>
            <?php show_error_messages("rule_error"); ?>
            <?php show_error_messages("username"); ?>
            <?php show_error_messages("password"); ?>
        </div>
    <?php } ?>
</section>