<?php
header("Access-Control-Allow-Origin: http://level2.local");
header("Access-Control-Allow-Credentials: true");

/**
 * the method retrieves the last existing id from the file in the system
 * and returns the incremented value.
 *
 * @return int id for new item
 */
function get_id()
{
    $file_name = "id.txt";
    if (file_exists($file_name)) {
        $count = (int)file_get_contents($file_name);
    } else {
        $count = 0;
    }
    $count++;
    file_put_contents($file_name, $count);
    return $count;
}

/**
 * @return mixed - string with text from new item
 */
function get_text_from_new_todo()
{
    $json = file_get_contents("php://input");
    $data = json_decode($json);
    return $data->{'text'};
}

/**
 *The method adds a task with id and text to an existing list.
 *
 * @param $new_text : text from new item, tat should be added
 * @param $new_id - int, unique of item
 */
function put_new_task_in_database($new_text, $new_id)
{
    $json = json_decode(file_get_contents("database.json"), true);
    $json{"items"}[] = array(
        "id" => $new_id,
        "text" => $new_text,
        "checked" => "true"
    );
    file_put_contents("database.json", json_encode($json));
}

$new_id = get_id();
$new_text = get_text_from_new_todo();
put_new_task_in_database($new_text, $new_id);
echo json_encode(array("id" => $new_id));

