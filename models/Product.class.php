<?php

require_once "Base.class.php";


class Product extends Base
{
	public $id,$name,$description,$price,$in_stock,$on_sale,$sale_price,$file_path;

	public static $min_count = 15;//min number of items in database at any time
    public static $min_on_sale_count = 3;//min on sale items
    public static $max_on_sale_count = 5;//max on sale items


    public function __construct()
    {

    }


    public static function select_count($query)//reusable function for multiple select queries
    {
    	$db = Base::get_connection();

    	try
    	{
    	if($stmt = $db->prepare($query))
    	{
    		$stmt->execute();
    		$count = $stmt->fetchColumn();

    		if($count>0)
    		{
    		 	return $count;
    		}
    		else
    		{
    			return NULL;
    		}
    	}
    	else
    	{
    		echo "Bad Database Connection";
    	}
    }
    catch(PDOException $e){
		echo $e->getMessage();
		die();
	  }

    }

    public static function get_count()//gets product count
    {
        return Product::select_count("SELECT COUNT(id) FROM products");
    }

    public static function get_on_sale_count()//get count of items on sale
    {
    	return Product::select_count("SELECT COUNT(on_sale) FROM products WHERE on_sale = TRUE");
    }

	public static function get_by_id($id)//get products by product_id
	{

	$query = "SELECT id, name, description, price, in_stock, file_path, on_sale, sale_price
                  FROM products
                  WHERE id = :id";

    $db = Base::get_connection();

    try
    {
    	if($stmt = $db->prepare($query))
    	{
    		$stmt->bindParam(":id",$id,PDO::PARAM_INT);
    		$stmt->execute();
    		$stmt->setFetchMode(PDO::FETCH_CLASS,"Product");
    		$item = $stmt->fetch();
			return $item;
		}
		else
    	{
    		return null;
    	}
    }
  	catch(PDOException $e)
  		{
			echo $e->getMessage();
			die();
	  	}
	}
	
	public function select_all($query)//reusable function for various select queries.
	{
		$db = Base::get_connection();

		try
		{
		if($stmt = $db->prepare($query))
		{
			$stmt->execute();
			$data = $stmt->fetchALL(PDO::FETCH_CLASS,"Product");
            // print_r($data);
			return $data;
		}
		else
		{
			echo "Bad Database Connection";
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
		die();
	  }
	}

	public static function get_all()//fetch all products from database
	{
		return Product::select_all("SELECT id, name, description, in_stock, file_path, price, sale_price, on_sale FROM products");
	}

  public static function get_all_on_sale()//fetch all on sale products
    {
        return Product::select_all("SELECT id, name, description, in_stock, file_path, price, sale_price, on_sale FROM products WHERE on_sale = TRUE");
    }

   public static function get_all_not_on_sale()//fetch all not on sale products
    {
        return Product::select_all("SELECT id, name, description, in_stock, file_path,price, sale_price, on_sale FROM products WHERE on_sale = FALSE");
    }

public static function get_not_on_sale_limit($limit_start, $limit_offset)//fetch on sale limit
    {
        $result = array();
        $query = "SELECT id, name, description, in_stock, file_path, price, sale_price, on_sale
                  FROM products
                  WHERE on_sale = FALSE
                  LIMIT :limit_start, :limit_offset";

        $db = Base::get_connection();
        try
        {
        if ($stmt = $db->prepare($query))
        {
        	$stmt->bindParam(":limit_start",$limit_start,PDO::PARAM_INT);
        	$stmt->bindParam(":limit_offset",$limit_offset,PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchALL(PDO::FETCH_CLASS,"Product");

            return $data;
        }
        else
        {
            echo "Bad Database Connection";
        }
    }
    catch(PDOException $e){
		echo $e->getMessage();
		die();
	  }
    }

    public function update()//update products for admin update
    {
        $query = "UPDATE products
                  SET name = :name,
                      description = :description,
                      price = :price,
                      in_stock = :in_stock,
                      file_path = :file_path,
                      on_sale = :on_sale,
                      sale_price = :sale_price
                  WHERE id = :id";

        $db = Base::get_connection();
        try
        {
        if($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":name",$this->name,PDO::PARAM_STR);
            $stmt->bindParam(":description",$this->description,PDO::PARAM_STR);
            $stmt->bindParam(":price",$this->price,PDO::PARAM_INT);
            $stmt->bindParam(":in_stock",$this->in_stock,PDO::PARAM_INT);
            $stmt->bindParam(":file_path",$this->file_path,PDO::PARAM_STR);
            $stmt->bindParam(":on_sale",$this->on_sale,PDO::PARAM_INT);
            $stmt->bindParam(":sale_price",$this->sale_price,PDO::PARAM_STR);
        	$stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
        	$stmt->execute();
        }
    }
    catch(PDOException $e){
		echo $e->getMessage();
		die();
	  }
	 }

	public function save()//insertion for admin insert.
    {
        $query = "INSERT INTO products(name, description, price,in_stock,file_path, on_sale, sale_price)
                  VALUES (:name, :description, :price, :in_stock, :file_path, :on_sale, :sale_price)";

        $db = Base::get_connection();
        try
        {
        if ($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":name",$this->name,PDO::PARAM_STR);
            $stmt->bindParam(":description",$this->description,PDO::PARAM_STR);
            $stmt->bindParam(":price",$this->price,PDO::PARAM_STR);
            $stmt->bindParam(":in_stock",$this->in_stock,PDO::PARAM_INT);
            $stmt->bindParam(":file_path",$this->file_path,PDO::PARAM_STR);
            $stmt->bindParam(":on_sale",$this->on_sale,PDO::PARAM_INT);
            $stmt->bindParam(":sale_price",$this->sale_price,PDO::PARAM_STR);

            $stmt->execute();

            // $this->id = $db->lastInsertId();
            return $this->id;
        }
    }
    catch(PDOException $e)
    {
    	echo $e->getMessage();
		die();
    }
    }

	public function reduce_quantity_in_stock_by($quantity_to_reduce)//reduce product quantity(add to cart)
    {
        if ($this->in_stock >= $quantity_to_reduce)
        {
            $this->in_stock -= $quantity_to_reduce;
        }
        else
        {
            echo "Not enough in stock";
        }
    }

    public function increase_quantity_in_stock_by($quantity_to_increase)//increase quantity but not used
    {
        if ($quantity_to_increase > 0)
        {
            $this->in_stock += $quantity_to_increase;
        }
        else
        {
            echo "Cannot increase by negative number";
        }
    }

    public function delete()
    {
        parent::delete("products");
    }
}



// $product = new Product();
// $id = $product->save();
// var_dump($id);
// $items = $product->get_count();
// echo "Total Products".$items;
// $sale_items = $product->get_on_sale_count();
// echo "Total Sale Products".$sale_items;

// $product_object = $product->get_by_id(2);
// print_r($product_object);

// $all_items = $product->get_all();
// print_r($all_items);

// $all_on_sale = $product->get_all_on_sale();
// print_r($all_on_sale);

// $all_not_on_sale = $product->get_all_not_on_sale();
// print_r($all_not_on_sale);

// $not_on_sale_limit = $product->get_not_on_sale_limit(0,10);
// print_r($not_on_sale_limit);

// $returned_id = $product->save();
// print_r($returned_id);

// $reduced = $product->reduce_in_stock_by(2);
// print_r($reduced);

// $product->increase_in_stock_by(2);

?>