<?php

function setTimeout(callable $cb, float $interval) {
    $then = microtime(true) + $interval;
    $intervalCb = function () use ($cb, $then, &$intervalCb) {
        if (microtime(true) >= $then) {
            $cb();
        } else {
            Loop::add($intervalCb);
        }
    };
    Loop::add($intervalCb);
}

function setInterval(callable $cb, float $interval) {
    $intervalCb = function () use ($cb, $interval, &$intervalCb) {
        setTimeout(function () use ($cb, &$intervalCb) {
            $cb();
            $intervalCb();
        }, $interval);
    };
    $intervalCb();
}

function wait(float $interval): Promise {
    return new Promise(function (callable $resolve) use ($interval) {
        setTimeout($resolve, $interval);
    });
}
