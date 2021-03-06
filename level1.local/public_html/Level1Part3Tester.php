<?php
if (count($argv) != 3) {
    die("usage: php tester.php <tasknum> <path/to/task.php>\nexample: php tester.php 1 t1.php\n");
}

$inputs = array(
    1 => <<<T1
GET /sum?nums=1,2,3,4,5 HTTP/1.1
Host: shpp.me
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.1
T1
,
    2 => <<<T2
POST /doc/test HTTP/1.1
Host: shpp.me
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.2
Content-Length: 35

bookId=12345&author=Tan+Ah+Teck
T2
,
    3 => <<<T3
GET /sum?nums=1,2,3,4 HTTP/1.1
Host: shpp.me
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.3
T3
,
    4 => <<<T4
GET /sum?words=ggg,fff HTTP/1.1
Host: shpp.me
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.3
T4
,
    5 => <<<T5
GET / HTTP/1.1
Host: student.shpp.me
Accept: image/gif, image/jpeg, */*
Accept-Language: en-us
Accept-Encoding: gzip, deflate
User-Agent: Mozilla/4.5

T5
);

// =============================================== ANSWERS

$answers = array(
    1 => <<<T1
HTTP/1.1 200 OK
Server: Apache/2.2.14 (Win32)
Content-Length: 2
Connection: Closed
Content-Type: text/html; charset=utf-8

15
T1
,
    2 => <<<T2
HTTP/1.1 400 Bad Request
Server: Apache/2.2.14 (Win32)
Content-Length: 11
Connection: Closed
Content-Type: text/html; charset=utf-8

bad request
T2
,
    3 => <<<T3
HTTP/1.1 200 OK
Server: Apache/2.2.14 (Win32)
Content-Length: 2
Connection: Closed
Content-Type: text/html; charset=utf-8

10
T3
,
    4 => <<<T4
HTTP/1.1 400 Bad Request
Server: Apache/2.2.14 (Win32)
Content-Length: 11
Connection: Closed
Content-Type: text/html; charset=utf-8

bad request
T4
,
    5 => <<<T5
HTTP/1.1 404 Not Found
Server: Apache/2.2.14 (Win32)
Content-Length: 9
Connection: Closed
Content-Type: text/html; charset=utf-8

not found
T5
);

for ($i = 1; $i < 6; $i++) {
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w")
    );

    $process = proc_open("php " . $argv[2], $descriptorspec, $pipes);

    if (is_resource($process)) {

        fwrite($pipes[0], $inputs[$i]);
        fclose($pipes[0]);

        $content = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $retval = proc_close($process);

        // remove date header Date: Sun, 18 Oct 2012 10:36:20 GMT
        $c2 = preg_replace("/Date: [^\n]+\n/", "", $content);
        $c2 = preg_replace("/\r/", "", $c2);

        echo "-----YOUR-RESPONSE-BEGIN--------------------\n";
        echo "(" . $c2 . ")\n";
        echo "-----YOUR-RESPONSE-END----------------------\n\n";

        $answers[$i] = preg_replace("/\r/", "", $answers[$i]);
        if ($c2 == $answers[$i]) {
            echo "response is correct :)\n";
        } else {
            echo "-----CORRECT-RESPONSE-BEGIN--------------------\n";
            echo "(" . $answers[$i] . ")\n";
            echo "-----CORRECT-RESPONSE-END----------------------\n\n";
            echo strlen($c2) . " vs " . strlen($answers[$i]) . "\n";

            echo "response is incorrect! SEE ABOVE\n";
        }
    }
}
