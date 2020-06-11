<?php
include_once  ROOT. '/models/Books.php';

class BooksController
{
    public function actionIndex()// просмотр списка книг
    {
        $booksList = array();
        $booksList = Books::getBooksList();
        require_once ROOT. '/views/books/books-page.html';
//        echo '<pre>';
//        print_r($booksList);
//        echo '<pre>';

        return true;
    }
    public function actionView($id)// просмотр одной книги.
    {
        if ($id){
            $book = Books::getBookById($id);
        }
        echo '<pre>';
        print_r($book);
        echo '<pre>';

        echo 'actionView';
        return true;
    }
}