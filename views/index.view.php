<?php
    require_once "shared/show_product_container.view.php";
    require "shared/show_error_messages.view.php";
?>
<?php
if (isset($_SESSION["user"]))
{?>

<section id="offers">
    <h2>Offers</h2>
    <?php show_product_container($context->products_on_sale); ?>
</section>
<section id="catalog">
    <h2>Catalog</h2>
    <?php show_product_container($context->products); ?>
    <div>
        <?php if (! $context->is_first_page ) { ?>
            <a class="button" href="index.php?page=<?= $context->prev_page ?>">Prev</a>
        <?php } ?>
        <?php if (! $context->is_last_page ) { ?>
            <a class="button" href="index.php?page=<?= $context->next_page ?>">Next</a>
        <?php } ?>
    </div>
</section>
<?}?>