<?php

require_once "Base.class.php";

class User extends Base
{
	public $id;
    public $email;
    public $password;
   
    public $role_id;
    public $role;

    public static $customer_role_id = 1;
    public static $customer_role_description = "customer";

    public function __construct()
    {

    }

    public function to_assoc_array()//returns array of user details
    {
        return array(
            "id" => $this->id,
            "email" => $this->email,
            //"password" => $this->password,
            // "nick_name" => $this->nick_name,
            "role" => $this->role,
        );
    }

    public static function create_customer($username, $password)//create customer in cart
    {
        $user_to_create = new User();

        $user_to_create->email = $username;
        $user_to_create->password = $password;
        $user_to_create->role_id = User::$customer_role_id;
        $user_to_create->role = User::$customer_role_description;

        $user_to_create->save();

        return $user_to_create;
    }

    public function save()//insert new customers in cart
    {
        $query = "INSERT INTO users(email, password,role_id)
                  VALUES (:email, :password,:role_id)";

        $db = Base::get_connection();

        try
        {
        if ($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":email",$this->email,PDO::PARAM_STR);
            $stmt->bindParam(":password",$this->password,PDO::PARAM_STR);
            $stmt->bindParam(":role_id",$this->role_id,PDO::PARAM_INT);
            $stmt->execute();

            // $this->id = $db->lastInsertId();
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

    public static function get_by($username, $password)//fetch users by username and password and role id
    {
        // TODO: Store encrypted passwords
        $query = "SELECT u.id, u.email, u.password,r.description
                  FROM users AS u
                  INNER JOIN roles AS r ON u.role_id = r.id
                  WHERE email = :email AND password = :password";

        $db = Base::get_connection();

        try
        {
        if ($stmt = $db->prepare($query))
        {
            $stmt->bindParam(":email", $username,PDO::PARAM_STR);

            $stmt->bindParam(":password", $password,PDO::PARAM_STR);

            // $user_to_return = new User();
            

            $stmt->setFetchMode(PDO::FETCH_CLASS,"User");

            $stmt->execute();

            
            
            $user_to_return = $stmt->fetchALL();


            return $user_to_return[0];
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



}
// $user = new User();
// $user->save();
// $returned = $user->get_by("mitayshh@gmail.com","mitayshh");
// var_dump($returned);



?>