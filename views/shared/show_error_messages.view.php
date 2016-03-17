<?php
    function show_error_messages($field_name)
    {
        global $context;
        
        if ($context->errors[$field_name])
        {
            echo '<ul class="error-messages">';
            foreach ($context->errors[$field_name] as $error_message)
            {
                echo "<li>" . $error_message . "</li>";
            }
            echo "</ul>";
        }
    }
?>