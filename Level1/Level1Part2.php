<?php


// не обращайте на эту функцию внимания
// она нужна для того чтобы правильно считать входные данные
function readHttpLikeInput()
{
    $f = fopen('php://stdin', 'r');
    $store = "";
    $toread = 0;
    while ($line = fgets($f)) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/', $line, $m))
            $toread = $m[1] * 1;
        if ($line == "\r\n")
            break;
    }
    if ($toread > 0)
        $store .= fread($f, $toread);
    return $store;
}

$contents = readHttpLikeInput();

function parseTcpStringAsHttpRequest($string)
{
    // find empty string to find body, if it present
    $pieces = explode("\n\n", $string);

    // find length of new array. if length == 1 - body is apsend, if length == 2 - body is present, else - it is error
    $length = count($pieces);

    // in my mint it need if some bloks are upsend. Field will stay empty
    $answer = array(
        "method" => "",
        "uri" => "",
        "headers" => "",
        "body" => "",
    );

    if ($length == 2) {
        $answer ["body"] = $pieces [1];
    }

    if ($length == 3) {
        echo "\n";
        echo "something is wrong";
    }

    //for future work substring should be splited by the \n
    $partWithoutBody = explode("\n", $pieces [0]);

    //work with first row
    $firstRow = explode(" ", $partWithoutBody [0]);
    $answer["method"] = $firstRow [0];
    $answer["uri"] = $firstRow [1];

    // next should work wits headers, its 1, 2... element in $partWithoutBody
    $headers = array();

    $countHeaders = count($partWithoutBody);

    for ($i = 1; $i < $countHeaders; $i++) {
        $oneHeader = explode(": ", $partWithoutBody [$i]);
        $headers[] = $oneHeader;
    }

    $answer ["headers"] = $headers;

    return $answer;
}

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));
