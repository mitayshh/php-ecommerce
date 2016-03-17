<?php

require_once "models/Product.class.php";
require "models/ShoppingCart.class.php";

class ProductService
{

	public static $page_size = 5;

    public function __construct()
    {

    }

    public function get_product_count()//fetch product count from model function
    {
        return Product::get_count();
    }

    public function get_page_size()//gets page size that is static
    {
        return ProductService::$page_size;
    }

    public function get_all_products()//fetch all products using model function
    {
        return Product::get_all();
    }

    public function get_products_on_sale()//fetch all products on sale using model function
    {
        return Product::get_all_on_sale();
    }

    public function get_products()//fetch all products not on sale using model function
    {
        return Product::get_all_not_on_sale();
    }

    public function get_products_not_on_sale_page($page_number)//used to set the product limit per page
    {
        $limit_start = ($page_number - 1) * ProductService::$page_size;
        $limit_offset = ProductService::$page_size;

        return Product::get_not_on_sale_limit($limit_start, $limit_offset);
    }

//adding product to cart and decrease quantity and update product
    public function add_product_to_cart($product_id, $user_id, $session_id)
    {
        $product_to_update = Product::get_by_id($product_id);

        $product_to_update->reduce_quantity_in_stock_by(1);
        $product_to_update->update(); 

        $shopping_cart = ShoppingCart::get_by($user_id, $session_id); //TODO: User Id and session
        // print_r($shopping_cart);
        // print_r($product_id);
        if (!$shopping_cart)
        {
            $shopping_cart = new ShoppingCart();
            $shopping_cart->user_id = $user_id; //TODO: User Id and session
            $shopping_cart->session_id = $session_id; //TODO: User Id and session
            $shopping_cart->save();
        }

        if ($shopping_cart->has_item($product_id))
        {
            // echo "HI";
            // print_r($shopping_cart->has_item($product_id));
            $shopping_cart->increment_item($product_id);
        }
        else
        {
            $shopping_cart->add_item($product_id);
        }

        $shopping_cart->calculate_total();
        $shopping_cart->update();
    }

    public function create_product($data)// creating product and checking sale constraint
    {
        $product_to_create = new Product();

        $product_to_create->name = htmlentities($data["name"]);
        $product_to_create->description = htmlentities($data["description"]);
        $product_to_create->price = $data["price"];
        $product_to_create->in_stock = $data["in_stock"];
        $product_to_create->sale_price = $data["sale_price"];
        $product_to_create->on_sale = $data["on_sale"];

        if ($product_to_create->on_sale)
        {
            $number_of_products_on_sale = Product::get_on_sale_count();

            if ($number_of_products_on_sale >= Product::$max_on_sale_count)
            {
                throw new Exception("Can't have more than " . Product::$max_on_sale_count . " products on discount.");
            }
        }

        if ($data["picture"]["name"])
        {
            $target_file = "img/products/" . basename($data["picture"]["tmp_name"] . "." .
                    pathinfo($data["picture"]["name"],PATHINFO_EXTENSION));

            move_uploaded_file($data["picture"]["tmp_name"], $target_file);
            //echo "move_uploaded_file();";

            $product_to_create->file_path = $target_file;
        }

        $product_to_create->save();
        //echo "product_to_update->save();";
    }

    public function update_product($data)//update product and checking sale and database constraint
    {
        $product_to_update = Product::get_by_id($data["product_id"]);
        // print_r($data["product_id"]);

        if ($product_to_update->on_sale != $data["on_sale"])
        {
            $number_of_products_on_sale = Product::get_on_sale_count();

            if ($data["on_sale"] && $number_of_products_on_sale >= Product::$max_on_sale_count)
            {
                throw new Exception("Can't have more than " . Product::$max_on_sale_count . " products on discount.");
            }
            if (!$data["on_sale"] && $number_of_products_on_sale <= Product::$min_on_sale_count)
            {
                throw new Exception("Can't have less than " . Product::$min_on_sale_count . " products on discount.");
            }
        }

        $product_to_update->name = htmlentities($data["name"]);
        $product_to_update->description = htmlentities($data["description"]);
        $product_to_update->price = $data["price"];
        $product_to_update->in_stock = $data["in_stock"];
        $product_to_update->sale_price = $data["sale_price"];
        $product_to_update->on_sale = $data["on_sale"];

        if ($data["picture"]["name"])
        {
            $target_file = "img/products/" . basename($data["picture"]["tmp_name"] . "." .
                    pathinfo($data["picture"]["name"],PATHINFO_EXTENSION));

            move_uploaded_file($data["picture"]["tmp_name"], $target_file);
            //echo "move_uploaded_file();";

            $product_to_update->file_path = $target_file;
        }

        $product_to_update->update();
        //echo "product_to_update->update();";
    }


    public function delete_product($product_id)//delete product and check sale constraint
    {
        $number_of_products = Product::get_count();

        if ($number_of_products > Product::$min_count)
        {
            $product_to_delete = Product::get_by_id($product_id);

            if ($product_to_delete->on_sale)
            {
                $number_of_products_on_sale = Product::get_on_sale_count();

                if ($number_of_products_on_sale <= Product::$min_on_sale_count)
                {
                    throw new Exception("Can't have less than " . Product::$min_on_sale_count . " products on discount.");
                }
            }

            $cart_items_to_delete = ShoppingCartItem::get_by_product_id($product_id);

            foreach ($cart_items_to_delete as $cart_item)
            {
                $cart_item->delete();
                //echo "cart_item->delete(); <br>";

                $shopping_cart = ShoppingCart::get_by_id($cart_item->shopping_cart_id);
                $shopping_cart->calculate_total();
                $shopping_cart->update();
                //echo "shopping_cart->update(); <br>";
            }

            $product_to_delete->delete();
            //echo "product_to_delete->delete(); <br>";
        }
        else
        {
            throw new Exception("Can't have less than " . Product::$min_count . " products.");
        }
    }
}





?>