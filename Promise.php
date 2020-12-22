<?php

class Promise {

    private Closure $doneCb;

    private Closure $errorCb;

    private $resolvedValue;

    private $resolvedError;

    private $resolved = false;

    private $errored = false;

    private Promise $next;

    public function __construct(callable $exec = null) {
        $this->doneCb = function () {};
        $this->errorCb = function () {};

        if ($exec) {
            Loop::add(function () use ($exec) {
                $exec(
                    fn ($v = null) => $this->resolve($v),
                    fn ($e = null) => $this->fail($e)
                );
            });
        }
    }

    public function then(callable $done, callable $error = null): self {
        $this->doneCb = Closure::fromCallable($done);
        $this->errorCb = is_null($error) ? function () {} : Closure::fromCallable($error);

        if (!isset ($this->next)) {
            $this->next = new Promise();
        }

        $this->tryCallbacks();

        return $this->next;
    }

    public function error(callable $error): self {
        return $this->then(function () {}, $error);
    }

    private function resolve($value = null) {
        $finish = function ($value) {
            $this->resolvedValue = $value;
            $this->resolved = true;
            $this->tryCallbacks();
        };
        if ($value instanceof Promise) {
            $value->then($finish);
        } else {
            $finish($value);
        }
    }

    private function fail($error = null) {
        $this->resolvedError = $error;
        $this->errored = true;
        $this->tryCallbacks();
    }

    private function tryCallbacks()
    {
        if ($this->doneCb && $this->resolved) {
            $v = ($this->doneCb)($this->resolvedValue);
            if (isset ($this->next)) {
                $this->next->resolve($v);
            }
        }
        if ($this->errorCb && $this->errored) {
            $e = ($this->errorCb)($this->resolvedError);
            if (isset ($this->next)) {
                $this->next->fail($e);
            }
        }
    }
}
