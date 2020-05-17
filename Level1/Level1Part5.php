<?php
//it is only theory, still cant test it

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
 * @param $string : HTTP request
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
    $filename = findHeaderHostAddress($headers) . findAddressFromUri($uri);

    return (file_exists($filename) ? answerGenerator(200, "OK", file_get_contents($filename)) :
        answerGenerator(404, "Not Found", "not found"));
}

/**
 * @param $headers : host header content
 * @return mixed|string - address from host header content ore "" if it is empty
 */
function findHeaderHostAddress($headers)
{
    for ($i = 0; $i < count($headers); $i++) {
        if ($headers[$i][0] === "Host") {
            $hostAddress = explode(".", $headers[$i][1], 2);
            return ($hostAddress[0] === "student" || $hostAddress[0] === "another") ? $hostAddress[0] . "/" : "else/";
        }
    }
    return "";
}

/**
 *the method extracts the address from uri for subsequent access
 *
 * @param $uri : corresponding HTTP request fragment
 * @return false|string address from uri? if it present, or "index.html"
 */
function findAddressFromUri($uri)
{
    return ($uri === "/") ? "index.html" : substr($uri, 1);
}

/**
 * the method makes a ready line - the HTTP answer
 *
 * @param $statuscode : sequence of numbers of status
 * @param $statusmessage : massage of status
 * @param $body : massage of status in lover case
 * @return string : generated HTTP response
 */
function answerGenerator($statuscode, $statusmessage, $body)
{
    $answerHeaders = "Server: Apache/2.2.14 (Win32)
Content-Length: size
Connection: Closed
Content-Type: text/html; charset=utf-8";
    return "HTTP/1.1 " . $statuscode . " " . $statusmessage . "\n" . str_replace("size", strval(strlen($body)), $answerHeaders . "\n\n" . $body);
}

$http = processHttpRequest($HttpRequest["method"], $HttpRequest["uri"], $HttpRequest["headers"], $HttpRequest["body"]);
echo($http);

