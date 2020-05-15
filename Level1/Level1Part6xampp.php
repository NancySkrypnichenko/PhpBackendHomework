<?php

/*
session_start();
$counter = isset($_SESSION['counter'])? $_SESSION['counter'] : 0;
$counter++;
$_SESSION["counter"] = $counter;
echo "You are logged in $counter time";tim
*/

// if we should use file_get_contents/file_put_contents and know if someone comes to our page in any time...
$file_name = "countfile.txt";
if (file_exists($file_name)) {
    $count = (int)file_get_contents($file_name);
} else {
    $count = 0;
}
file_put_contents($file_name, $count + 1);
echo $count;
