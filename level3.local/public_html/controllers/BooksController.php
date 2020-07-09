<?php
include_once ROOT . '/models/Book.php';

class BooksController

{


    public function actionIndex($shift)// просмотр списка книг
    {

        $booksList = Book::getBooksList($shift);
        include_once ROOT . '/views/booksList.php';
        return true;
    }

    public function actionView($id)// просмотр одной книги.
    {
        if ($id) {
            $book = Book::getBookById($id);
            include_once ROOT . '/views/bookItem.php';
        }

        return true;
    }

    public function actionStatistic($id)
    {
        $book = Book::increaseNumberOfBookRequest($id);
    }

    public function actionSearch($string)
    {

        $booksList = Book::search($string);
        include_once ROOT . '/views/booksList.php';
        return true;
    }
}