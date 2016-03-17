<?php

abstract class Base
{

	private static $connection;

	protected static function get_connection()//reusable function for connection to database
	{
		if(!isset(Base::$connection))
		{
			$hostname = "localhost";
			$username = "mxd3549";//for kelvin its mxd3549
			$password = "fr1end";//for kelvin its fr1end
			$database = "mxd3549";
			try
			{
			Base::$connection = new PDO("mysql:host=$hostname;dbname=$database",$username,$password);
			Base::$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			// echo "Connected";
			}

			catch(PDOException $e)
			{
				die("Bad Database Connection");
			}
	}

		return Base::$connection;
	}

public function delete($table)//for dynamic deletion of data from child classes
{

	$query = "delete from $table where id = :id";

	$db = Base::get_connection();

try
{
	if($stmt=$db->prepare($query))
	{
		$stmt->bindParam(":id",$this->id,PDO::PARAM_INT);
		$stmt->execute();

		// if($stmt->error())
		// {
		// 	echo "Error Deleting From Database";
		// }
	}
	else
	{
		echo "Error Connection with Database";
	}
}
catch(PDOException $e)
{
	echo "Error Deleting from Database";
}

}

}

// class test extends Base
// {

// 	public function __construct()
// 	{

// 	}

// 	public static function connection()
// 	{
// 		$db = Base::get_connection();
// 		$data = array();
// 		$query = "select * from roles";
// 		$stmt = $db->prepare($query);
// 		$stmt->execute();
// 		while($row = $stmt->fetch())
// 		{
// 			$data[] = $row;
// 		}
// 		print_r($data);
// 	}
// }
// $test = new test;
// $test->connection();

?>