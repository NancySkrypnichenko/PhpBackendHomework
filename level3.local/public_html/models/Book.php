<?php
require_once $_SERVER["DOCUMENT_ROOT"] . 'public_html/config/connect_bd.php';

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

        $row = $statement->fetch();

        return $row;
    }

    /**
     * Returns an array of books
     * @param int $shift
     * @return array
     */
    public static function getBooksList($shift)
    {
        $db_connect = ConnectBD::connectDB();
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
        $count = $statement->rowCount();

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
            //соединение с БД
            $new_picture_path = "../../static/Images/" . $array_from_post_request['uploadedPicture'];
            move_uploaded_file("../../static/PreFiles/" . $array_from_post_request['uploadedPicture'], $new_picture_path);

            $book_name = $array_from_post_request['book_name'];
            $year = $array_from_post_request['year'];
            $authors = array();
            array_push($authors, $array_from_post_request['author_1']);
            array_push($authors, $array_from_post_request['author_2']);
            array_push($authors, $array_from_post_request['author_3']);

            var_dump($authors);

            $db_connect = ConnectBD::connectDB();
            $query = 'INSERT INTO books (book_name, year, picture) VALUES (:book_name, :year, :picture)';

            $data = $db_connect->prepare($query);
            $data->bindParam(":book_name", $book_name, PDO::PARAM_STR);
            $data->bindParam(":year", $year, PDO::PARAM_INT);
            $data->bindParam(":picture", $new_picture_path, PDO::PARAM_STR);

            $data->execute();
            // получать id  последней вставки
            $bookId = $db_connect->lastInsertId();

            foreach ($authors as $key => $author) {

                if (strlen($author) == 0) {
                    continue;
                }
                $query = 'INSERT INTO authors (author_name) VALUES (:author_name)';
                $data = $db_connect->prepare($query);
                $data->bindParam(":author_name", $author, PDO::PARAM_STR);
                $data->execute();
                $authorId = $db_connect->lastInsertId();

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
            $query = $db_connect->prepare("SELECT * from books where book_name  LIKE :string or author_name LIKE :string;");
            $string = '%' . urldecode($string) . '%';

            echo $string;

            $query->bindValue(":string", $string, PDO::PARAM_STR);
            $query->execute();
            $count = $query->rowCount();

            $books_list = array();
            for ($i = 0; $row = $query->fetch(); $i++) {
                $books_list[$i]['id'] = $row['id'];
                $books_list[$i]['book_name'] = $row['book_name'];
                $books_list[$i]['year'] = $row['year'];
                $books_list[$i]['picture'] = $row['picture'];
                $books_list[$i]['author_name'] = $row['author_name'];
                $books_list[$i]['number_of_clicks'] = $row['number_of_clicks'];
                $books_list[$i]['shift'] = 0;
                $books_list[$i]['count'] = $count;

            }
            return $books_list;

        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
        return true;
    }
}
