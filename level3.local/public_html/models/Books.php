<?php
require_once '/home/nancy/www/level3.local/public_html/config/connect_bd.php';

class Books
{

    /**
     * returns single book item with specified id
     * @param integer $id
     * @return array
     */
    public static function getBookById($id)
    {
        $book_as_array = array();
        $db_connect = ConnectBD::connectDB();
        $query = sprintf('select * from books where id = ?');
        $statement = $db_connect->prepare($query);
        $statement->bindValue(1, $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();

        $book_as_array['id'] = $row['id'];
        $book_as_array['book_name'] = $row['book_name'];
        $book_as_array['year'] = $row['year'];
        $book_as_array['picture'] = $row['picture'];
        $book_as_array['author_name'] = $row['author_name'];

        return $book_as_array;
    }

    /**
     * Returns an array of books
     * @param int $shift
     * @return array
     */
    public static function getBooksList($shift)
    {
        $db_connect = ConnectBD::connectDB();
        // запрос на получение общего количества книг
        $query = "SELECT * FROM books WHERE is_active = 1";
        $statement = $db_connect->prepare($query);
        $statement->execute();
        $count = $statement->rowCount();


        $books_list = array();
        // получем первые?? 20 книг
        $query = sprintf('select * from books where is_active = 1 limit ?,?');
        $statement = $db_connect->prepare($query);
        $statement->bindValue(1, 10 * $shift, PDO::PARAM_INT);
        $statement->bindValue(2, 10, PDO::PARAM_INT);
        $statement->execute();

        // нужно их переложить в массив поудобнее
        //$tables = $statement->fetchAll(PDO::FETCH_NUM);


        for ($i = 0; $row = $statement->fetch(); $i++) {
            $books_list[$i]['id'] = $row['id'];
            $books_list[$i]['book_name'] = $row['book_name'];
            $books_list[$i]['year'] = $row['year'];
            $books_list[$i]['picture'] = $row['picture'];
            $books_list[$i]['author_name'] = $row['author_name'];
            $books_list[$i]['shift'] = $shift;
            $books_list[$i]['count'] = $count;

        }
        //$books_list['shift'] = $shift; // сдвиг, какая партия книг должна быть показана
        return $books_list;
    }
}
