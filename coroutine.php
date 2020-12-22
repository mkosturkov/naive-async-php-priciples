<?php

function goco(callable $coroutine) {
    $iter = $coroutine();
    $tick = function () use ($iter, &$tick) {
        if (!$iter->valid()) {
            return;
        }
        $iter->current()
            ->then(function ($v) use ($iter, &$tick) {
                $iter->send($v);
                $tick();
            });
    };
    $tick();
};
