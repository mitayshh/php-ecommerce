<?php
if (isset($_SESSION["user"]))
{?>
<section id="offers">
    <h2>Your Shopping Cart</h2>
    
        <?php if ($context->cart->items) { ?>
        <table style="width:80%" border="1">
        <tr>
            <td>Name</td>
            <td>Description</td>
            <td>Quantity</td>
            <td>Price</td>
        </tr>
            <?php foreach ($context->cart->items as $cart_item) { ?>
                
                <?php foreach ($cart_item->product as $product_item) { ?>
                <tr>
                    <td><?= $product_item->name ?></td>
                    
                        <td><?= $product_item->description ?></td>
                        <td>You ordered: <?= $product_item->product_quantity ?></td>
                    
                        <?php if ($product_item->on_sale) { ?>
                                
                                <td>$ <?= $product_item->sale_price ?></td>
                        <?php } else { ?>
                            <td><span class="price">$ <?= $product_item->price ?></span></td>
                        <?php } ?>
                 </tr>
                <?php }?>
                
            <?php } ?>
            </table>
        <?php } else { ?>
            <h3 class="cart-empty-message">
                Your cart is empty! Go <a href="index.php">here</a> if you want to purchase something.
            </h3>
        <?php } ?>
        <p class="total-price">
            <span>Total: $ <?= $context->cart->total_price ?></span>
        </p>
        <div class="buttons">
            <form method='POST' action='cart.php'>
                <input type='submit' name='clear_cart_submit' value='Delete Item'>
                <input type='button' name='add_to_cart_submit' value='Checkout' onclick="alert('Will be implemented in next release given the opportunity :)');">
            </form>
        </div>
    
</section>
<?}?>