<?php

//function for reading input correctly
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

$HttpRequest = parseTcpStringAsHttpRequest($contents);

/**
 * the method generates an http response depending on the incoming parameters (request)
 *
 * @param $method : of the HTTP request
 * @param $uri : of the HTTP request
 * @param $headers : of the HTTP request
 * @param $body : request body content
 * @return string http response
 */
function processHttpRequest($method, $uri, $headers, $body)
{
    $answerHeaders = "Server: Apache/2.2.14 (Win32)
Content-Length: size
Connection: Closed
Content-Type: text/html; charset=utf-8";

    if ($method == "GET") {
        $indexOfSum = strpos($uri, "/sum");

        if ($indexOfSum === FALSE) {
            return answerGenerator(404, "Not Found", $answerHeaders, "not found");
        }

        $indexOfNums = strpos($uri, "?nums");

        if ($indexOfNums === FALSE) {
            return answerGenerator(400, "Bad Request", $answerHeaders, "bad request");
        }
        $partOfUri = explode("=", $uri);
        $numbersFromUri = explode(",", $partOfUri[1]);
        $result = 0;
        foreach ($numbersFromUri as $value) {
            $result += (int)$value;
        }
        return answerGenerator(200, "OK", $answerHeaders, strval($result));
    }
    return answerGenerator(400, "Bad Request", $answerHeaders, "bad request");
}

/**
 * the method makes a ready line - the HTTP answer
 *
 * @param $statuscode : sequence of numbers of status
 * @param $statusmessage : massage of status
 * @param $headers : headers in answer
 * @param $body : massage of status in lover case
 * @return string : generated HTTP response
 */
function answerGenerator($statuscode, $statusmessage, $headers, $body)
{
    return "HTTP/1.1 " . $statuscode . " " . $statusmessage . "\n" . str_replace("size", strval(strlen($body)), $headers) . "\n\n" . $body;
}

$http = processHttpRequest($HttpRequest["method"], $HttpRequest["uri"], $HttpRequest["headers"], $HttpRequest["body"]);
echo($http);

