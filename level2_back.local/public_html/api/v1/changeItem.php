<?php
header("Access-Control-Allow-Origin: http://level2.local");
header("Access-Control-Allow-Credentials: true");

/**
 * the method processes the incoming parameters and collects them into an array
 *
 * @return array in which the first element is the desired id,
 * the second element is new text
 * third item is  item status (true/false)
 */
function find_todo_to_change()
{
    $json = file_get_contents("php://input");
    $data = json_decode($json);
    return array($data->{'id'}, $data->{'text'}, $data->{'checked'});
}

/**
 * the method takes an array with id parameters and new text and replaces the text specified by task in the file
 *
 * @param $about_new_todo_information_array : in which the first element is the desired id,
 * the second element is new text
 * third item is  item status (true/false)
 */
function change_tex_in_todo($about_new_todo_information_array)
{
    $json = json_decode(file_get_contents("database.json"), true);
    foreach ($json{"items"} as $key => $value) {
        if ($value{"id"} === $about_new_todo_information_array[0]) {
            $json["items"][$key]["text"] = $about_new_todo_information_array[1];
            $json["items"][$key]["checked"] = $about_new_todo_information_array[2];
            file_put_contents("database.json", json_encode($json));
            break;
        }
    }
}

change_tex_in_todo(find_todo_to_change());
echo json_encode(array("ok" => "true"));
