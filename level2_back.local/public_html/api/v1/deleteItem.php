<?php
header("Access-Control-Allow-Origin: http://level2.local");
header("Access-Control-Allow-Credentials: true");

/**
 * @return mixed - int. id of item to delete
 */
function get_id_to_delete()
{
    $json = json_decode(file_get_contents("php://input"));
    return $json->{'id'};
}

/**
 * method removes the task with the specified id from the existing task list
 *
 * @param $id int. id of item to delete
 */
function delete_todo_by_id($id)
{
    $json = json_decode(file_get_contents("database.json"), true);
    foreach ($json{"items"} as $key => $value) {
        if ($value{"id"} === $id) {
            array_splice($json["items"], $key, 1);
            file_put_contents("database.json", json_encode($json));
            break;
        }
    }
}

delete_todo_by_id(get_id_to_delete());
echo json_encode(array("ok" => "true"));
