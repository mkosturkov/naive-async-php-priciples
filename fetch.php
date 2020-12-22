<?php

function fetchUrl(string $url, callable $done, callable $onerror) {
    $fp = @stream_socket_client("tcp://$url:80", $errno, $errstr, 30);
    if (!$fp) {
        $onerror($errstr);
        return;
    }
    stream_set_blocking($fp, false);
    fwrite($fp, "GET / HTTP/1.0\r\nHost: $url\r\nAccept: */*\r\n\r\n");

    $content = '';
    $read = function () use ($fp, $done, &$content, &$read, $url) {
        $bytes = fgets($fp, 100);
        $content .= $bytes;
        if (!feof($fp)) {
            Loop::add($read);
        } else {
            fclose($fp);
            $done($content);
        }
    };
    Loop::add($read);
}

function pfetchUrl(string $url): Promise {
    return new Promise(
        function (callable $resolve, callable $error) use ($url) {
            fetchUrl($url, $resolve, $error);
        }
    );

}
