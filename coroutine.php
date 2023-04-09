<?php

function goco(callable|Generator $coroutine) {
    return new Promise(function ($resolve) use ($coroutine) {
        $iter = $coroutine instanceof Generator ? $coroutine : $coroutine();
        $tick = function () use ($iter, $resolve, &$tick) {
            if (!$iter->valid()) {
                $resolve($iter->getReturn());
                return;
            }
            $yielded = $iter->current();
            if ($yielded instanceof Promise) {
                $yielded->then(function ($v) use ($iter, &$tick) {
                    $iter->send($v);
                    $tick();
                });
            }
        };
        $tick();
    });
};

function defer(callable $coroutine) {
    $fiber = new Fiber($coroutine);
    Loop::add(fn () => $fiber->start());
}
