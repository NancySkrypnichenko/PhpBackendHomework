<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/config/connect_bd.php';

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
        $query = sprintf(" SELECT `b`.`id`, `b`.`book_name`, `b`.`year`, `b`.`picture`, GROUP_CONCAT(`a`.`author_name` SEPARATOR ', ') 
            AS `author`
            FROM `books` AS `b`
            LEFT JOIN `pairs` AS `ab` ON `ab`.`id_books` = `b`.`id`
            LEFT JOIN `authors`AS `a` ON `a`.`id` = `ab`.`id_authors`
            WHERE `b`.`id` = ?
        
        ");
        // $query = sprintf('select * from books where id = ?');
        $statement = $db_connect->prepare($query);
        $statement->bindValue(1, $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
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


        $query = "SELECT books.id, books.book_name, books.year, books.picture, books.number_of_clicks, 
                   GROUP_CONCAT(authors.author_name SEPARATOR ', ') 
            AS author
            FROM books 
            LEFT JOIN pairs  
            ON pairs.id_books = books.id
            LEFT JOIN authors
            ON authors.id = pairs.id_authors
            WHERE books.is_active = 1
            GROUP BY books.id
            LIMIT ?, ?";

        // запрос на получение общего количества книг
        $statement = $db_connect->prepare($query);
        $statement->bindValue(1, 10 * $shift, PDO::PARAM_INT);
        $statement->bindValue(2, 10, PDO::PARAM_INT);
        $statement->execute();


        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        $row ['shift'] = $shift;
        $row ['count'] = $count;
        return $row;

    }

    /**
     * add new book to database.
     * @param $array_from_post_request
     * @return bool
     */
    public static function addNewBook($array_from_post_request)
    {
        try {
            if (isset ($array_from_post_request['uploadedPicture'])) {

                $picture_name = ($array_from_post_request['uploadedPicture']);
                $old_path = ROOT . "/static/PreFiles/" . $picture_name;
                $new_picture_path = ROOT . "/static/Images/" . "1" . $picture_name;
                rename($old_path, $new_picture_path);
            } else {
                $new_picture_path = "../../static/Images/default_image.jpg";
            }

            $book_name = $array_from_post_request['book_name'];
            $year = $array_from_post_request['year'];
            $authors = array();

            $db_connect = ConnectBD::connectDB();

            $query = 'INSERT INTO books (book_name, year, picture) VALUES (:book_name, :year, :picture)';

            $data = $db_connect->prepare($query);
            $data->bindParam(":book_name", $book_name, PDO::PARAM_STR);
            $data->bindParam(":year", $year, PDO::PARAM_INT);
            $data->bindParam(":picture", $new_picture_path, PDO::PARAM_STR);

            $data->execute();
            // получать id  последней вставки
            $bookId = $db_connect->lastInsertId();

            for ($i = 1; $i <= 3; $i++) {

                $a_name = $array_from_post_request['author_' . $i];

                if (strlen($a_name) == 0) { // if book has only 1 or two authors
                    continue;
                }

                //should be sure that author does not already exist in the table database
                $query = 'SELECT id FROM authors WHERE author_name = :author_name';
                $data = $db_connect->prepare($query);
                $data->bindParam(":author_name", $a_name, PDO::PARAM_STR);
                $data->execute();
                $id = $data->fetch();


                if (!$id) { // if author isn`t in database put him there

                    $query = 'INSERT INTO authors (author_name) VALUES (:author_name)';
                    $data = $db_connect->prepare($query);
                    $data->bindParam(":author_name", $a_name, PDO::PARAM_STR);
                    $data->execute();
                    $authorId = $db_connect->lastInsertId();


                } else { // if author already in database - use his id to added new book
                    $authorId = $id["id"];
                }

                $query = 'INSERT INTO pairs (id_books, id_authors) VALUES (:book_name, :author_name)';
                $data = $db_connect->prepare($query);
                $data->execute([
                    'book_name' => $bookId,
                    'author_name' => $authorId
                ]);
            }
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


    public static function search($string)
    {
        try {

            $db_connect = ConnectBD::connectDB();
            $query = "SELECT books.id, books.book_name, books.year, books.picture, books.number_of_clicks, 
                   GROUP_CONCAT(authors.author_name SEPARATOR ', ') 
            AS author
            FROM books 
            LEFT JOIN pairs  
            ON pairs.id_books = books.id
            LEFT JOIN authors
            ON authors.id = pairs.id_authors
            WHERE books.is_active = 1 and book_name  LIKE :string or books.is_active = 1 and author_name LIKE :string
            GROUP BY books.id";
            $data = $db_connect->prepare();
            $string = '%' . urldecode($string) . '%';

            echo $string;

            $data->bindValue(":string", $string, PDO::PARAM_STR);
            $data->execute();
            $count = $data->rowCount();

            $row = $data->fetchAll(PDO::FETCH_ASSOC);

            $row ['shift'] = 0;
            $row ['count'] = $count;
            return $row;

        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    }
}
