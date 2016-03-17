<?php

require "services/UserService.class.php";
require "BaseController.class.php";
require "helpers/ValidationHelper.class.php";
require "helpers/HttpHelper.class.php";

class AuthenticationController extends BaseController
{
    private $service;

    public function __construct($context)
    {
        $this->context = $context;
        $this->service = new UserService();
    }

    public function get()
    {

    }

    public function post()
    {
        if (isset($_POST["login_submit"]))
        {
            if ($this->is_valid())
            {
                $this->try_login("authenticate");
            }
        }
        if (isset($_POST["register_submit"]))
        {
            if ($this->is_valid())
            {
                $this->try_login("register");
            }
        }
        if (isset($_POST["logout_submit"]))
        {
            session_unset();
            session_destroy();
            $params = session_get_cookie_params();
            setcookie(session_name(), '', 1, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
            session_regenerate_id(true);

            HttpHelper::redirect("index.php");
        }
    }

    public function try_login($auth_method)
    {
        try
        {
            if ($auth_method == "authenticate")
            {
                $authenticated_user = $this->service->authenticate($_POST);
            }
            elseif ($auth_method == "register")
            {
                $authenticated_user = $this->service->register($_POST);
            }

            $_SESSION["user"] = $authenticated_user->to_assoc_array();

            HttpHelper::redirect("index.php");
        }
        catch (Exception $ex)
        {
            $this->context->errors["rule_error"][] = $ex->getMessage();
        }
    }

    public function is_valid()
    {
        $validator = new ValidationHelper();

        if (!$validator->validate_required($_POST["username"]))
        {
            $this->context->errors["username"][] = "Username required.";
        }
        if (!$validator->validate_required($_POST["password"]))
        {
            $this->context->errors["password"][] = "Password required.";
        }

        if (!$validator->validate_email($_POST["username"]))
        {
            $this->context->errors["username"][] = "Username must be a valid email.";
        }

        # If there are no errors, then the input is valid
        return empty($this->context->errors);
    }
}

?>