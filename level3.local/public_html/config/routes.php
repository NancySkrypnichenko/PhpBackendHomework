<?php
return array(
    'book/([0-9]+)' => 'books/view/$1/$2',// action view in BooksController
    'books/([0-9]+)' => 'books/index/$1/$2', // action index in BooksController
    'books' => 'books/index/0/0', // action index in BooksController

);
