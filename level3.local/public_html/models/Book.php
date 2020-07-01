<?php
require_once '/home/nancy/www/level3.local/public_html/config/connect_bd.php';

class Book
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
        for ($i = 0; $row = $statement->fetch(); $i++) {
            $books_list[$i]['id'] = $row['id'];
            $books_list[$i]['book_name'] = $row['book_name'];
            $books_list[$i]['year'] = $row['year'];
            $books_list[$i]['picture'] = $row['picture'];
            $books_list[$i]['author_name'] = $row['author_name'];
            $books_list[$i]['number_of_clicks'] = $row['number_of_clicks'];
            $books_list[$i]['shift'] = $shift;
            $books_list[$i]['count'] = $count;

        }
        return $books_list;
    }

    /**
     * add new book to database.
     * @param $array_from_post_request
     * @return bool
     */
    public static function addNewBook($array_from_post_request)
    {
        try {
            //соединение с БД
            print_r($array_from_post_request);

            $db_connect = ConnectBD::connectDB();
            $query = 'INSERT INTO books (book_name, year, picture, author_name) VALUES (:book_name, :year, :picture, :author_name)';

            $book_name = $array_from_post_request['book_name'];
            $year = $array_from_post_request['year'];
            $picture = $array_from_post_request['uploadedPicture'];
            $author_name = $array_from_post_request['author_1'];

            $data = $db_connect->prepare($query);
            $data->bindParam(":book_name", $book_name, PDO::PARAM_STR);
            $data->bindParam(":year", $year, PDO::PARAM_INT);
            $data->bindParam(":picture", $picture, PDO::PARAM_STR);
            $data->bindParam(":author_name", $author_name, PDO::PARAM_STR);
            $data->execute();
        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
        return true;
    }

    /**
     * delete book from database
     * @param $id
     * @return bool
     */
    public static function deleteBook($id)
    {
        try {
            $db_connect = ConnectBD::connectDB();
            $query = $db_connect->prepare("UPDATE books SET is_active = 0 WHERE id = :id");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
        return true;
    }

    public static function increaseNumberOfBookRequest($id)
    {
        try {
            $db_connect = ConnectBD::connectDB();
            $query = $db_connect->prepare("UPDATE books SET number_of_clicks = number_of_clicks +1 WHERE id = :id;");
            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
        return true;
    }
}
