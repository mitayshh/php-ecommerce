<?php
    require_once "show_product.view.php";

    function show_product_container($products_to_show)
    {
        echo "<ul class='product-container'>";
        foreach ($products_to_show as $product)
        {
            show_product($product);
        }
        echo "</ul>";
    }
?>