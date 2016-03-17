<?php

require_once "Base.class.php";


class ShoppingCartItem extends Base
{

	public $id;
    public $product_id;
    public $cart_id;
    public $product_quantity;

    public $product;

    public function __construct()
    {

    }

    public static function select_by($query,$id_lookup)//reusable function for select using id
    {
    	// $result = array();

    	$db = Base::get_connection();
    	// echo "hi";
    	try
    	{
    		$data = array();
    		if($stmt = $db->prepare($query))
    		{
    		$stmt->bindParam(":id",$id_lookup,PDO::PARAM_INT);
    		$stmt->execute();
    		$stmt->setFetchMode(PDO::FETCH_CLASS,"ShoppingCartItem");
    		$result= $stmt->fetchAll();
    		return $result;
    		}
    		else
    		{
    			echo "Bad Database Connection";
    		}
    	}
    	catch(PDOException $e)
    	{
    		echo $e->getMessage();
			die();
    	}

    }

    public static function get_by($cart_id)//fetch shopping cart by cart id
    {
        return ShoppingCartItem::select_by("SELECT id,cart_id, product_id, product_quantity
                                            FROM shopping_cart
                                            WHERE cart_id = :id", $cart_id);
    }

    public static function get_by_product_id($product_id)//fetch shopping cart by product id
    {
        return ShoppingCartItem::select_by("SELECT id,cart_id, product_id, product_quantity
                                            FROM shopping_cart
                                            WHERE product_id = :id", $product_id);
    }

    public function delete()
    {
        parent::delete("shopping_cart");
    }

    // public function trial()
    // {
    // 	$db = Base::get_connection();
    // 	$query = "SELECT * from shopping_cart where cart_id = :id";
    // 	//$id = 1;
    // 	// $stmt = $db->prepare($query);
    // 	// $stmt->bindParam(":id",1,PDO::PARAM_INT);
    // 	$returneddata = $this->select_by($query,1);
    // 	var_dump($returneddata);

    // }


}
// $query = "SELECT * from shopping_cart where cart_id = :id";
// $ShoppingCartItem = new ShoppingCartItem();
// $returned = $ShoppingCartItem->get_by(1);
// $ShoppingCartItem -> trial();
// $returneddata = $ShoppingCartItem->get_by_product_id(1);
// var_dump($returned);




?>