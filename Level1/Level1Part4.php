<?php


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

    //work whis first row
    $firstRow = explode(" ", $partWithoutBody [0]);
    $answer["method"] = $firstRow [0];
    $answer["uri"] = $firstRow [1];

    // next shoul work whis headers, its 1, 2... element in $partWithoutBody
    $headers = array();
    $countHeaders = count($partWithoutBody);

    for ($i = 1; $i < $countHeaders; $i++) {
        $oneHeader = explode(": ", $partWithoutBody [$i]);
        $headers[] = $oneHeader;
    }

    $answer ["headers"] = $headers;
    return $answer;
}

$HttpRequest = parseTcpStringAsHttpRequest($contents);

function processHttpRequest($method, $uri, $headers, $body)
{
    $answerHeaders = "Server: Apache/2.2.14 (Win32)
Content-Length: size
Connection: Closed
Content-Type: text/html; charset=utf-8";

    if ($method === "POST" && $uri === "/api/checkLoginAndPassword") {
        $loginAndPassvord = explode("&", $body);
        $login = explode("=", $loginAndPassvord[0]);
        $passvord = explode("=", $loginAndPassvord[1]);

        $arhive = file("passwords.txt", FILE_SKIP_EMPTY_LINES);
        if ($arhive === FALSE) {
            return answerGenerator(200, "OK", $answerHeaders, "<h1 style=\"color:green\">FOUND</h1>");
        }

        if (in_array($login[1] . ":" . $passvord[1], $arhive)) {
            return answerGenerator(500, "Internal Server Error", $answerHeaders, "internal server error");
        }
        return answerGenerator(403, "Forbidden", $answerHeaders, "forbidden");
    }
    return answerGenerator(400, "Bad Request", $answerHeaders, "bad request");
}

function answerGenerator($statuscode, $statusmessage, $headers, $body)
{
    return "HTTP/1.1 " . $statuscode . " " . $statusmessage . "\n" . str_replace("size", strval(strlen($body)), $headers) . "\n\n" . $body;
}

$http = processHttpRequest($HttpRequest["method"], $HttpRequest["uri"], $HttpRequest["headers"], $HttpRequest["body"]);
echo($http);

