<?php
include_once  ROOT. '/models/Books.php';

class BooksController

{


    public function actionIndex($shift)// просмотр списка книг
    {
        $booksList = Books::getBooksList($shift);
        include_once ROOT . '/views/books/index.php';
        return true;
    }

    public function actionView($id)// просмотр одной книги.
    {
        if ($id) {
            $book = Books::getBookById($id);
            include_once ROOT . '/views/OneBook/index.php';
        }

        return true;
    }
}