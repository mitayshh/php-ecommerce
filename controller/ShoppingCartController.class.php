<?php

require "BaseController.class.php";
require "services/ShoppingCartService.class.php";

class ShoppingCartController extends BaseController
{
    private $service;

    public function __construct($context)//constructor and creates object so shoppingcartservice
    {
        $this->context = $context;
        $this->service = new ShoppingCartService();
    }

    public function get()//if get than load data
    {
        $this->load_data();
    }

    public function post()//if post than clear cart using and reload data
    {
        if (isset($_POST["clear_cart_submit"]))
        {
            $this->service->clear_cart($_SESSION["user"]["id"], session_id()); 
        }

        $this->load_data();
    }

    public function load_data()//load data
    {
        $this->context->cart = $this->service->get_cart($_SESSION["user"]["id"], session_id());
    }
  }

?>