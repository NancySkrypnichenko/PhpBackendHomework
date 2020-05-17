//front should be in another file, index.php
<form action="checkLoginAndPassword.php" method="POST">

    <p><input name="login" placeholder="Login"></p>
    <p><input name="password" type="password" placeholder="Password"></p>

    <input name="Enter" type="submit" value=" Enter "/>
</form>

<?php
if (isset($_POST['Enter'])) {
    $headers = getallheaders();

    if ($headers['Content-Type'] === "application/x-www-form-urlencoded" &&
        $_SERVER["REQUEST_URI"] === "/checkLoginAndPassword.php") {

        if (file_exists("passwords.txt")) {
            $arhive = file("passwords.txt", FILE_SKIP_EMPTY_LINES);
            echo findDatesInFile($arhive);
        }
    } else {
        echo "400, Bad Request";
    }
}

/**
 * @param $arhive array with pair login:password from file
 * @return string - html code to answer user was his dates in file
 */
function findDatesInFile($arhive)
{
    foreach ($arhive as $value) {
        if ($_POST["login"] . ":" . $_POST["password"] === preg_replace("/\n/", "", $value)) {
            return "<h1 style=\"color:green\">FOUND</h1>";
        }
    }
    return "<h1 style=\"color:red\">NOTFOUND</h1>";
}

