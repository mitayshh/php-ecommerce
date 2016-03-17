<?php

require_once "Base.class.php";
require "ShoppingCartItem.class.php";
require_once "Product.class.php";

class ShoppingCart extends Base
{

	public $id;
    public $user_id;
    public $session_id;
    public $total_price;

    public $items;



    public function __construct()
    {


    }


    public static function get_by($user_id,$session_id)//fetch shopping_cart by userid and session id
    {
    	$query = "SELECT id, user_id, session_id, total_price
                  FROM shopping_carts
                  WHERE user_id = :user_id OR session_id = :session_id";

        $db = Base::get_connection();
        // try
        // {
        if($stmt = $db->prepare($query))
        {
        	$stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
        	$stmt->bindParam(":session_id",$session_id,PDO::PARAM_INT);
        	$stmt->execute();
        	$stmt->setFetchMode(PDO::FETCH_CLASS,"ShoppingCart");
        	$cart_to_return = $stmt->fetch();
        	return $cart_to_return;
        }
        else
        {
        	echo "Bad Database Connection";
        }
    // }
  //   catch(PDOException $e)
  //   {
  //   	echo $e->getMessage();
		// die();
  //   }
   }


   public function get_by_id($shopping_cart_id)//fetch shopping cart by cart id
   {
   	$query = "SELECT id, user_id, session_id, total_price
                  FROM shopping_carts
                  WHERE id = :id";

	$db = Base::get_connection();

    try
    {

    	if($stmt = $db->prepare($query))
    	{
    		$stmt->bindParam(":id",$shopping_cart_id,PDO::PARAM_INT);
    		$stmt->execute();
    		$stmt->setFetchMode(PDO::FETCH_CLASS,"ShoppingCart");
    		$cart_to_return = $stmt->fetch();
    		return $cart_to_return;
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

   public function save()//insert into shopping carts
   {
   	$query = "INSERT INTO shopping_carts(user_id, session_id)
                  VALUES (:user_id, :session_id)";

    $db = Base::get_connection();
    try
    {
    if ($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":user_id",$this->id, PDO::PARAM_INT);
            $stmt->bindParam(":session_id",$this->session_id, PDO::PARAM_INT);

            $stmt->execute();

            $this->id = $db->lastInsertId();
            // return $this->id;
        }

        if(!stmt)
        {
        	print_r($db->errorInfo());
        }
     }
     catch(PDOException $e)
     {
     	echo $e->getMessage();
		die();
     }
   }

   public function update()//update shopping cart by id
   {

   	$query = "UPDATE shopping_carts
                  SET user_id = :user_id, session_id = :session_id, total_price = :total_price
                  WHERE id = :id";

    $db = Base::get_connection();

    try
    {
    	if($stmt = $db->prepare($query))
    	{
    		$stmt->bindParam(":user_id",$this->user_id,PDO::PARAM_INT);
    		$stmt->bindParam(":session_id",$this->session_id,PDO::PARAM_INT);
    		$stmt->bindParam(":total_price",$this->total_price,PDO::PARAM_INT);
    		$stmt->bindParam(":id",$this->id,PDO::PARAM_INT);

    		$stmt->execute();
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

//fetch products where it exists in particular shopping cart.//Used to display cart items at checkout in front end
   public function get_items()
    {
        $this->items = array();
        $query = "SELECT sci.id, sci.product_id, sci.cart_id, sci.product_quantity,
                         p.name, p.description, p.on_sale, p.price, p.sale_price, p.in_stock, p.file_path
                  FROM shopping_cart AS sci
                  INNER JOIN products AS p ON sci.product_id = p.id
                  WHERE sci.cart_id = :id";

        $db = Base::get_connection();

        if ($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"ShoppingCartItem");
            $item = $stmt->fetch();
            // print_r($item);
        }
        else
        {
            throw new Exception("No connection with the DB");
        }
        if ($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Product");
            $product = $stmt->fetchALL();
            // print_r($product);
            $item->product = $product;
            // print_r($item);
            $this->items[] = $item;
            // print_r($this->items);
            return $this->items;
        }
        else
        {
            throw new Exception("No connection with the DB");
        }
 
    }

    public function has_item($product_id)//check whether a product exists in shopping cart
    {
    	$query = "SELECT id
                  FROM shopping_cart
                  WHERE cart_id =:id  AND product_id = :product_id";

        $db = Base::get_connection();

        try
        {
        	if($stmt = $db->prepare($query))
        	{
        		$stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        		$stmt->bindParam(":product_id",$product_id,PDO::PARAM_INT);

        		$stmt->execute();

        		if($stmt->fetchcolumn()>0)
        		{
        			return True;
        		}
        		else
        		{
        			return False;
        		}
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

    public function increment_item($product_id)//increament item count in shopping cart for same item
    {
    	$query = "UPDATE shopping_cart
                  SET product_quantity = product_quantity + 1
                  WHERE cart_id = :id and product_id = :product_id";


        $db = Base::get_connection();

        try
        {
        	if($stmt = $db->prepare($query))
        	{
        		$stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        		$stmt->bindParam(":product_id",$product_id,PDO::PARAM_INT);

        		$stmt->execute();
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

    public function add_item($product_id)//add item in eisting shopping cart
    {
    	$query = "INSERT INTO shopping_cart(product_id,cart_id, product_quantity)
                  VALUES (:product_id,:id, 1)";

        $db = Base::get_connection();

        try
        {
        	if($stmt = $db->prepare($query))
        	{
        		$stmt->bindParam(":product_id",$product_id,PDO::PARAM_INT);
				$stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
                // $stmt->bindParam(":product_id",$product_id,PDO::PARAM_INT);
        		$stmt->execute();
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

    public function calculate_total()//calculate total of cart
    {
    	$items = ShoppingCartItem::get_by($this->id);

    	$new_total = 0;

    	foreach ($items as $item)
        {
            $product = Product::get_by_id($item->product_id);
            if ($product->on_sale)
            {
                $new_total += $product->sale_price * $item->product_quantity;
            }
            else
            {
                $new_total += $product->price * $item->product_quantity;
            }
        }

        $this->total_price = $new_total;
    }
}

// $shopping_cart = new ShoppingCart();
// $returned = $shopping_cart->get_by(11,0);
// $returned_by_id = $shopping_cart->get_by_id(1);
// $returned_id = $shopping_cart->save();
// $shopping_cart->update();
// $returned = $shopping_cart->get_items();
// $bool = $shopping_cart->has_item(1);
// $shopping_cart->increment_item(1);
// $shopping_cart->add_item(1);
// var_dump($bool);
// print_r($returned);



?>