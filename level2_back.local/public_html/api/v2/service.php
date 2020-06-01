<?php

function report_about_authorization_error($string_answer)
{
    http_response_code(400);
    echo json_encode(array("error" => $string_answer));
    exit;
}

function clean_input_text($value = "")
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);

    return $value;
}
