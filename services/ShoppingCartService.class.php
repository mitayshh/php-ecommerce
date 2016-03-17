<?php

require "models/ShoppingCart.class.php";

class ShoppingCartService
{
    public function __construct()
    {

    }

    public function get_cart($user_id, $session_id)//get cart by user_id and session_id
    {
        $shopping_cart = ShoppingCart::get_by($user_id, $session_id);
        // print_r($shopping_cart);

        if ($shopping_cart)
        {
            $shopping_cart->get_items();
        }

        return $shopping_cart;
    }

    public function clear_cart($user_id, $session_id)//clear cart 
    {
        $shopping_cart = ShoppingCart::get_by($user_id, $session_id);

        // print_r($shopping_cart);
        if ($shopping_cart)
        {
            foreach ($shopping_cart->get_items() as $cart_item)
            {
            // print_r($cart_item);
            foreach($cart_item->product as $product)
            {
            // print_r($cart_item->product_quantity);
            $product->increase_quantity_in_stock_by($product->product_quantity);
            $product->update();
            $cart_item->delete();
            }
        }

            // print_r($shopping_cart);
            $shopping_cart->calculate_total();
            $shopping_cart->update();
        }
    }
}