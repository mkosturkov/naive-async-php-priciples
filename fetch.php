<?php

class URLFetcher
{
    public string $content = '';
    private $fp;
    public function __construct(
        private string $url,
        private Closure $done,
        private Closure $onerror
    ) {}

    public function start(): void
    {
        $this->fp = @stream_socket_client("tcp://$this->url:80", $errno, $errstr, 30);
        if (!$this->fp) {
            ($this->onerror)($errstr);
        }
        stream_set_blocking($this->fp, false);
        fwrite($this->fp, "GET / HTTP/1.0\r\nHost: $this->url\r\nAccept: */*\r\n\r\n");
        Loop::add(fn () => $this->tick());
    }

    public function readSomeBytes(): void
    {
        $this->content .= fgets($this->fp, 100);
    }

    public function isDone(): bool
    {
        return feof($this->fp);
    }

    public function tick(): void
    {
        if ($this->isDone()) {
            fclose($this->fp);
            ($this->done)($this->content);
        } else {
            $this->readSomeBytes();
            Loop::add(fn () => $this->tick());
        }
    }
}

function fetchUrl(string $url, callable $done, callable $onerror) {
    $fetcher = new URLFetcher($url, Closure::fromCallable($done), Closure::fromCallable($onerror));
    Loop::add(fn () => $fetcher->start());
}

function _fetchUrl(string $url, callable $done, callable $onerror) {
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

function ffetchUrl(string $url): string {
    $fiber = Fiber::getCurrent();
    fetchUrl(
        $url,
        fn ($value) => $fiber->resume($value),
        fn ($err) => $fiber->throw(new Exception($err))
    );
    return Fiber::suspend();
}

