<?php
class ValidationHelper
{
    public function __construct() { }

    public function validate_required($value)
    {
        return isset($value) && trim($value) !== "";
    }

    public function validate_email($value)
    {
        # php magic validates emails.
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function validate_max_length($value, $max_length)
    {
        return strlen(trim($value)) <= $max_length;
    }

    public function validate_number($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    public function validate_equals($value, $equal_value)
    {
        return $value == $equal_value;
    }

    public function validate_checked($value)
    {
        return $value == "1";
    }

    public function validate_file_is_picture($file)
    {
        $allowed_types =  array("image/jpeg","image/png" ,"image/gif");

        $is_valid = in_array($file["type"], $allowed_types);
        $is_valid &= getimagesize($file["tmp_name"]);

        return $is_valid;

    }

    public function validate_file_is_present($file)
    {
        return $file["name"] !== "";
    }
}
?>