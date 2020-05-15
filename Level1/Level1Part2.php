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

/**
 * the method divides the HTTP request in the form of a string into structural fragments
 * and returns an array with the corresponding values
 *
 * @param $string HTTP request
 * @return string[] array with Parsed HTTP request
 */
function parseTcpStringAsHttpRequest($string)
{
    // find empty string to find body, if it present
    $pieces = explode("\n\n", $string);

    // it need to correct order of element in the result
    $answer = array(
        "method" => "",
        "uri" => "",
        "headers" => "",
        "body" => "",
    );

    if (count($pieces) === 2) {
        $answer ["body"] = $pieces [1];
    }

    //for future work substring should be splited by the \n
    $partWithoutBody = explode("\n", $pieces [0]);

    //work with first row
    $firstRow = explode(" ", $partWithoutBody [0]);
    $answer["method"] = $firstRow [0];
    $answer["uri"] = $firstRow [1];

    // next should work wits headers, its 1, 2... element in $partWithoutBody
    $headers = array();

    for ($i = 1; $i < count($partWithoutBody); $i++) {
        $headers[] = explode(": ", $partWithoutBody [$i]);
    }
    $answer ["headers"] = $headers;

    return $answer;
}

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));

