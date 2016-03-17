<?php

require "models/User.class.php";
require "models/ShoppingCart.class.php";


class UserService
{

	public function __construct()
	{


	}

	public function register($data)//if new user is registered although not used. Some minor error.
    {
        try
        {
            $user_to_return = User::create_customer($data["username"], $data["password"]);

            $this->reassign_shopping_cart($user_to_return);

            // $shopping_cart = new ShoppingCart();
            // print_r($shopping_cart);
            // print_r($user_to_return);
            // $shopping_cart->save($user_to_return->id);

            return $user_to_return;
        }
        catch (Exception $ex)
        {
            throw new Exception("Username already exists.");
        }
    }

    public function authenticate($data)//authenticate once user is logged in and check it it's admin
    {
        $value = $data["username"];
        if($value == "admin@gmail.com")
        {
            $_SESSION["user"]["role"] = "admin";
            // print_r($data["username"]);
            header('Location:admin.php');
        }
        else
        {
        $user_to_return = User::get_by($data["username"], $data["password"]);
        // print_r($data["username"]);
        // print_r("authenticate".$data["username"]);
        // print_r($user_to_return);

        $this->reassign_shopping_cart($user_to_return);

        if ($user_to_return)
        {
            return $user_to_return;
        }
        else
        {
            throw new Exception("Wrong username and password.");
        }
    }
    }

    public function reassign_shopping_cart($user)//reassign user shopping cart
    {
        $shopping_cart = ShoppingCart::get_by($user->id, session_id());
        // print_r("reassign_shopping_cart".$user->id);
        print_r($shopping_cart);

        if ($shopping_cart)
        {
            // echo "hi";
            $shopping_cart->session_id = session_id();
            $shopping_cart->user_id = $user->id;
            $shopping_cart->update();
        }
    }

}

?>