<?php

abstract class BaseController
{
    protected $context;

    public function process_request()//reusable function to check if it's get or post method of child class
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $this->get();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->post();
        }
    }

    public function redirect_if_not_admin()//if not admin than redirect to index page
    {
        if ($_SESSION["user"]["role"] != "admin")
        {
            HttpHelper::redirect("index.php");
        }
    }

    abstract public function get();
    abstract public function post();
}
?>