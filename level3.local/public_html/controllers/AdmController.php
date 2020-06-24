<?php
include_once ROOT . '/models/Book.php';
session_start();

class AdmController

{
    public function actionLogout()

    {
        $_SESSION['authorisation'] = false;
        $_SESSION['wasload'] = false;
        echo " по крайней мере путь работает";
        header("Location: http://level3.local/books/");

        echo " по крайней мере путь работает";
        return true;
    }

    public function actionForm($shift)

    {
        $login = 'login';
        $password = '123';


        if (isset ($_SESSION['entered']) && $_SESSION['entered'] == 'successful') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                print_r($_POST);
                if (!isset ($_POST['id'])) {
                    $booksList = Book::addNewBook($_POST);
                } else {
                    $booksList = Book::deleteBook($_POST['id']);
                }
            }
        } else {

            if ($_SESSION ['wasload'] && isset ($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $password) {
                //нужно звать контроллер, показывающий админку
                echo "я сюда попасть не могу?";
                $_SESSION['authorisation'] = true;

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    print_r($_POST);
                    if (!isset ($_POST['id'])) {
                        $booksList = Book::addNewBook($_POST);
                    } else {
                        $booksList = Book::deleteBook($_POST['id']);
                    }
                }

                $booksList = Book::getBooksList($shift);
                include_once ROOT . '/views/Admin/form.php';
            } else {

                header('WWW-Authenticate: Basic realm="Restricted Area"');
                header('HTTP/1.0 401 Unauthorized');
                if (!isset($_SERVER['PHP_AUTH_USER'])) {
                    echo 'я таки вот тут';
                    header("Location: http://level3.local/books/");
                }
                $_SESSION['wasload'] = true;
            }

        }
    }

    function login()
    {
        $login = 'login';
        $password = '123';
        if ($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $password) {
            //нужно звать контроллер, показывающий админку
            $_SESSION['enterd'] = 'successful';
        } else {
            header('WWW-Authenticate: Basic realm="Restricted Area"');
            header('HTTP/1.0 401 Unauthorized');
        }
        return true;
    }
}