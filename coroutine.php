<?php

function co(Generator $iter) {
    return new Promise(function ($resolve) use ($iter) {
        $push = function ($v) use ($iter, &$tick) {
            $iter->send($v);
            $tick();
        };

        $tick = function () use ($iter, $resolve, &$push) {
            if (!$iter->valid()) {
                $resolve($iter->getReturn());
                return;
            }
            $yielded = $iter->current();
            if ($yielded instanceof Promise) {
                $yielded->then($push);
            } else if ($yielded instanceof Generator) {
                co($yielded)->then($push);
            } else {
                $push($yielded);
            }
        };
        $tick();
    });
}

function goco(callable $coroutine) {
    $value = $coroutine();
    if ($value instanceof Generator) {
        return co($value);
    }
    return new Promise(fn ($r) => $r($value));
};

function defer(callable $coroutine) {
    $fiber = new Fiber($coroutine);
    Loop::add(fn () => $fiber->start());
}
