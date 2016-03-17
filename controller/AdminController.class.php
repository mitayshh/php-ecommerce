<?php

require "services/ProductService.class.php";
require "BaseController.class.php";
require "helpers/ValidationHelper.class.php";
require "helpers/HttpHelper.class.php";

class AdminController extends BaseController
{
    private $service;

    public function __construct($context)//constructor and create product service object
    {
        $this->context = $context;
        $this->service = new ProductService();
    }

    public function get()//if get than check if it's admin and load data
    {
        $this->redirect_if_not_admin();

        $this->load_data();
    }

    public function post()// if post than edit/delete/create the product for admin
    {
        $this->redirect_if_not_admin();
        // echo "Redirect if not admin";

        if (isset($_POST["edit_product_submit"]))
        {
            if ($this->edit_product_is_valid())
            {
                // echo "valid product";
                try
                {
                    // echo "valid product";
                    $update_data = array(
                        "product_id" => $_POST["product_id"],
                        "name" => $_POST["name" . $_POST["product_id"]],
                        "description" => $_POST["description" . $_POST["product_id"]],
                        "price" => $_POST["price" . $_POST["product_id"]],
                        "in_stock" => $_POST["in_stock" . $_POST["product_id"]],
                        "sale_price" => $_POST["sale_price" . $_POST["product_id"]],
                        "on_sale" => $_POST["on_sale" . $_POST["product_id"]],
                        "picture" => $_FILES["picture" . $_POST["product_id"]]
                    );
                    // print_r($update_data);

                    $this->service->update_product($update_data);
                }
                catch (Exception $ex)
                {
                    $this->context->errors["rule_error" . $_POST["product_id"]][] = $ex->getMessage();
                }
            }
        }
        elseif (isset($_POST["delete_product_submit"]))
        {
            if ($this->delete_product_is_valid())
            {
                try
                {
                    $this->service->delete_product($_POST["product_id"]);
                }
                catch (Exception $ex)
                {
                    $this->context->errors["rule_error" . $_POST["product_id"]][] = $ex->getMessage();
                }
            }
        }
        elseif (isset($_POST["create_product_submit"]))
        {
            if ($this->create_product_is_valid())
            {
                try
                {
                    $create_data = $_POST;
                    $create_data["picture"] = $_FILES["picture"];

                    $this->service->create_product($create_data);
                }
                catch (Exception $ex)
                {
                    $this->context->errors["create_rule_error"][] = $ex->getMessage();
                }
            }
        }

            $this->load_data();
    }

    public function load_data()//load all products
    {
        $this->context->products = $this->service->get_all_products();
    }

    public function edit_product_is_valid()//check for validation of data entered during product edit
    {
        $validator = new ValidationHelper();

        if (!$validator->validate_required($_POST["product_id"]))
        {
            $this->context->errors["product_id"][] = "Product Id is required.";
        }
        if (!$validator->validate_required($_POST["name" . $_POST["product_id"]]))
        {
            $this->context->errors["name" . $_POST["product_id"]][] = "Please enter the product's Name.";
        }
        if (!$validator->validate_required($_POST["description" . $_POST["product_id"]]))
        {
            $this->context->errors["description" . $_POST["product_id"]][] = "Please enter the product's Description.";
        }
        if (!$validator->validate_required($_POST["price" . $_POST["product_id"]]))
        {
            $this->context->errors["price" . $_POST["product_id"]][] = "Please enter the product's Price.";
        }
        if (!$validator->validate_required($_POST["in_stock" . $_POST["product_id"]]))
        {
            $this->context->errors["in_stock" . $_POST["product_id"]][] = "Please enter the product's Stock quantity.";
        }
        if (!$validator->validate_required($_POST["on_sale" . $_POST["product_id"]]))
        {
            $this->context->errors["on_sale" . $_POST["product_id"]][] = "Please specify whether the product's On Discount.";
        }

        if (!$validator->validate_number($_POST["product_id"]))
        {
            $this->context->errors["product_id" ][] = "Not a valid Product Id.";
        }
        if (!$validator->validate_number($_POST["price" . $_POST["product_id"]]))
        {
            $this->context->errors["price" . $_POST["product_id"]][] = "Not a valid Price.";
        }
        if (!$validator->validate_number($_POST["in_stock" . $_POST["product_id"]]))
        {
            $this->context->errors["in_stock" . $_POST["product_id"]][] = "Not a valid Stock quantity.";
        }

        if ($validator->validate_checked($_POST["on_sale" . $_POST["product_id"]]))
        {
            if (!$validator->validate_required($_POST["sale_price" . $_POST["product_id"]]))
            {
                $this->context->errors["sale_price" . $_POST["product_id"]][] =
                    "A Discount price is required if the product is On Discount.";
            }
        }

        if ($validator->validate_file_is_present($_FILES["picture" . $_POST["product_id"]]))
        {
            if (!$validator->validate_file_is_picture($_FILES["picture" . $_POST["product_id"]]))
            {
                $this->context->errors["picture" . $_POST["product_id"]][] = "Only image files are allowed.";
            }
        }

        # If there are no errors, then the input is valid
        return empty($this->context->errors);
    }

    public function delete_product_is_valid()//check for validation during delete product
    {
        $validator = new ValidationHelper();

        if (!$validator->validate_required($_POST["product_id"]))
        {
            $this->context->errors["product_id"][] = "Product Id is required.";
        }

        if (!$validator->validate_number($_POST["product_id"]))
        {
            $this->context->errors["product_id" ][] = "Not a valid Product Id.";
        }

        # If there are no errors, then the input is valid
        return empty($this->context->errors);
    }

    public function create_product_is_valid()//check for validation during product creation
    {
        $validator = new ValidationHelper();
        
        if (!$validator->validate_required($_POST["name"]))
        {
            $this->context->errors["name"][] = "Please enter the product's Name.";
        }
        if (!$validator->validate_required($_POST["description"]))
        {
            $this->context->errors["description"][] = "Please enter the product's Description.";
        }
        if (!$validator->validate_required($_POST["price"]))
        {
            $this->context->errors["price"][] = "Please enter the product's Price.";
        }
        if (!$validator->validate_required($_POST["in_stock"]))
        {
            $this->context->errors["in_stock"][] = "Please enter the product's Stock quantity.";
        }
        if (!$validator->validate_required($_POST["on_sale"]))
        {
            $this->context->errors["on_sale"][] = "Please specify whether the product's On Discount.";
        }

        if (!$validator->validate_number($_POST["price"]))
        {
            $this->context->errors["price"][] = "Not a valid Price.";
        }
        if (!$validator->validate_number($_POST["in_stock"]))
        {
            $this->context->errors["in_stock"][] = "Not a valid Stock quantity.";
        }

        if ($validator->validate_checked($_POST["on_sale"]))
        {
            if (!$validator->validate_required($_POST["sale_price"]))
            {
                $this->context->errors["sale_price"][] =
                    "A Discount price is required if the product is On Discount.";
            }
        }

        if ($validator->validate_file_is_present($_FILES["picture"]))
        {
            if (!$validator->validate_file_is_picture($_FILES["picture"]))
            {
                $this->context->errors["picture"][] = "Only image files are allowed.";
            }
        }

        # If there are no errors, then the input is valid
        return empty($this->context->errors);
    }
}