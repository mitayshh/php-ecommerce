<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title; ?></title>
        <link rel="stylesheet" href="css/style.css">
        
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <div id="main-container">
            <h1>Online Store</h1>
            <section id="navigation">
                <ul id="navigation-container">
                    <li class="navigation-item">
                                <?php
                    if (isset($_SESSION["user"]))
                    {?>
                        <a class="button" href="index.php">Catalog</a>
                    </li>
                    <?php $value = $_SESSION["user"]["role"]; if($value != "admin"){?>
                    <li class="navigation-item">
                        <a class="button" href="cart.php">Cart</a>
                    </li><?}?>
                    <?}?>
                    <?php if ($_SESSION["user"]["role"] == "admin") { ?>
                        <li class="navigation-item">
                            <a class="button" href="admin.php">Admin</a>
                        </li>
                    <?php } ?>

                    <?php if (!isset($_SESSION["user"])) { ?>
                        <li class="navigation-item">
                            <?php include "login_form.view.php"; ?>
                        </li>
                    <?php } else { ?>
                        <li class="navigation-item">
                            <span class="welcome-message">Welcome  <?= $_SESSION["user"]["email"] ?></span>
                        </li>
                        <li class="navigation-item">
                            <form method="POST" action="login.php">
                                <input type="submit" name="logout_submit" value="Log Out">
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </section>
            <?php include($page_content); ?>
        </div>
    </body>
</html>