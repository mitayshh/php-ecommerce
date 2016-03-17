<?php
    require "shared/show_error_messages.view.php";
?>
<section>
    <h2>Add New Product</h2>
    <div class="control-panel create-product-container">
        <form method="POST" action="admin.php" enctype="multipart/form-data">
            <p>
                Name:
                <input type="text" name="name" 
                       class="<?= ($context->errors["name"]) ? "validation-error" : "" ?>"
                       value="<?= $_POST["name"] ?>"/>
            </p>
            <p>
                Picture:
                <input type="file" name="picture" 
                       class="<?= ($context->errors["picture"]) ? "validation-error" : "" ?>">
            </p>
            <p>
                <span class="description-label">Description:</span>
                <textarea class="<?= ($context->errors["description"]) ? "validation-error" : "" ?>"
                          name="description" ><?= $_POST["description"] ?></textarea>
            </p>
            <p>
                Regular price:
                <input type="number" name="price" 
                       class="<?= ($context->errors["price"]) ? "validation-error" : "" ?>"
                       value="<?= $_POST["price"] ?>"/>
                Stock:
                <input type="number" name="in_stock" 
                       class="<?= ($context->errors["in_stock"]) ? "validation-error" : "" ?>"
                       value="<?= $_POST["in_stock"] ?>"/>
            </p>
            <p>
                Discount price:
                <input type="number" name="sale_price" 
                       class="<?= ($context->errors["sale_price"]) ? "validation-error" : "" ?>"
                       value="<?= $_POST["sale_price"] ?>"/>
                On Discount?:
                <input type="hidden" name="on_sale" value="0">
                <input type="checkbox" name="on_sale" value="1"
                       class="<?= ($context->errors["on_sale"]) ? "validation-error" : "" ?>"
                    <?= $_POST["on_sale"] ? "checked" : "" ?>/>
            </p>
            <?php if ($context->errors) { ?>
                <div>
                    <?php show_error_messages("create_rule_error"); ?>
                    <?php show_error_messages("name"); ?>
                    <?php show_error_messages("picture"); ?>
                    <?php show_error_messages("description"); ?>
                    <?php show_error_messages("price"); ?>
                    <?php show_error_messages("in_stock"); ?>
                    <?php show_error_messages("sale_price"); ?>
                    <?php show_error_messages("on_sale"); ?>
                </div>
            <?php } ?>
            <div>
                <input type='submit' name='create_product_submit' value='Save'>
            </div>
        </form>
    </div>
</section>

<section id="products">
    <h2>Available Products</h2>
    <div class="control-panel">
        <ul class="product-list-container">
            <?php foreach ($context->products as $product) { ?>
                <li class="product-list-item">
                    <form method="POST" action="admin.php" enctype="multipart/form-data">
                        <p>
                            <input type="text" name="name<?= $product->id ?>" 
                                   class="<?= ($context->errors["name" . $product->id]) ? "validation-error" : "" ?>"
                                   value="<?= $product->name ?>"/>
                        </p>
                        <div class="picture">
                            <img src="<?= $product->file_path ?>" alt="<?= $product->name ?>"/>
                        </div>
                        <p>
                            <input type="file" name="picture<?= $product->id ?>"
                                   class="<?= ($context->errors["picture" . $product->id]) ? "validation-error" : "" ?>">
                        </p>
                        <p>
                            <textarea class="<?= ($context->errors["description" . $product->id]) ? "validation-error" : "" ?>"
                                      name="description<?= $product->id ?>" ><?= $product->description ?></textarea>
                        </p>
                        <p>

                            Regular price:
                            <input type="number" name="price<?= $product->id ?>" 
                                   class="<?= ($context->errors["price" . $product->id]) ? "validation-error" : "" ?>"
                                   value="<?= $product->price ?>"/>
                            Stock:
                            <input type="number" name="in_stock<?= $product->id ?>" 
                                   class="<?= ($context->errors["in_stock" . $product->id]) ? "validation-error" : "" ?>"
                                   value="<?= $product->in_stock ?>"/>
                        </p>
                        <p>
                            Discount price:
                            <input type="number" name="sale_price<?= $product->id ?>" 
                                   class="<?= ($context->errors["sale_price" . $product->id]) ? "validation-error" : "" ?>"
                                   value="<?= $product->sale_price ?>"/>
                            On Discount?:
                            <input type="hidden" name="on_sale<?= $product->id ?>"  value="0">
                            <input type="checkbox" name="on_sale<?= $product->id ?>" value="1"
                                   class="<?= ($context->errors["on_sale" . $product->id]) ? "validation-error" : "" ?>"
                                   <?= $product->on_sale ? "checked" : "" ?>/>
                        </p>
                        <?php if ($context->errors) { ?>
                            <div>
                                <?php show_error_messages("rule_error" . $product->id); ?>

                                <?php show_error_messages("name" . $product->id); ?>
                                <?php show_error_messages("description" . $product->id); ?>
                                <?php show_error_messages("price" . $product->id); ?>
                                <?php show_error_messages("sale_price" . $product->id); ?>
                                <?php show_error_messages("is_on_sale" . $product->id); ?>
                                <?php show_error_messages("quantity_in_stock" . $product->id); ?>
                                <?php show_error_messages("picture" . $product->id); ?>
                            </div>
                        <?php } ?>
                        <div>
                            <input type="hidden" name="product_id" value="<?= $product->id ?>">
                            <input type='submit' name='edit_product_submit' value='Save'>
                            <input type='submit' name='delete_product_submit' value='Delete'>
                        </div>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </div>
</section>