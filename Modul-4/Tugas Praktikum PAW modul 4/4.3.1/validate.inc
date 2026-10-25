<?php

function validateName(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) ||
        trim($field_list[$field_name]) == "") {

        $errors[$field_name] = "required";
    }
    else if (!preg_match("/^[a-zA-Z'-]+$/", $field_list[$field_name])) {

        $errors[$field_name] = "invalid";
    }
}

?>
