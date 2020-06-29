<?php
include_once ROOT . '/models/Book.php';

class AdmController
//exampleuser
//123

{
    public function actionForm($shift)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset ($_POST['id'])) {
                $booksList = Book::addNewBook($_POST);
            } else {
                $booksList = Book::deleteBook($_POST['id']);
            }
        }
        $booksList = Book::getBooksList($shift);
        include_once ROOT . '/views/Admin/form.php';
    }

    public function actionSaveFile()
    {
        $response = array();
        $response['status'] = 'bad';
        if ($_FILES ['file']['error'] == 0) {

            if ((!empty($_POST)) && (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) ;
            header('Content-Type: application/json; charset=utf-8');


            if (!empty($_FILES['file']['tmp_name'])) {

                $upload_path = $_SERVER["DOCUMENT_ROOT"] . "/static/PreFiles/";
                $user_filename = $_FILES['file']['name'];
                $tmp_name = $_FILES['file']['tmp_name'];
                $userfile_basename = pathinfo($user_filename, PATHINFO_FILENAME) . time();
                $userfile_extension = pathinfo($user_filename, PATHINFO_EXTENSION);
                $server_filename = $userfile_basename . "." . $userfile_extension;
                $server_filepath = $upload_path . $server_filename;

                if (move_uploaded_file($tmp_name, $server_filepath)) {
                    $response['status'] = 'ok';
                    $response['fileName'] = $server_filename;
                }
            }
        } else {
            return $response;
        }
        echo json_encode($response);
    }
}