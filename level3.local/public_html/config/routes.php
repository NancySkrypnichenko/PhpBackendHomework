<?php
return array(
    'book/([0-9]+)' => 'books/view/$1/$2',// action view in BooksController
    'books/([0-9]+)' => 'books/index/$1/$2', // action index in BooksController
    'books/search' => 'books/search',
    'books' => 'books/index/0/0', // action index in BooksController
    'adm/form/([0-9]+)' => 'adm/form/$1/$2', // action Form in admСontroller
    'adm/form' => 'adm/form/0/0', // action Form in admСontroller
    'book/want/([0-9]+)' => 'books/statistic/$1/$2',
    'adm/saveFile' => 'adm/saveFile',
);
