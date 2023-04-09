<?php

class Promise {

    private $resolvedValue;

    private bool $resolved = false;

    private $rejectedError;

    private bool $rejected = false;

    /** @var array{'done': callable, 'error': callable, 'promise': Promise}[]  */
    private array $queuedHandlers = [];

    private function isResolved(): bool
    {
        return $this->resolved;
    }

    private function isRejected(): bool
    {
        return $this->rejected;
    }

    private function isComplete(): bool
    {
        return $this->isRejected() || $this->isResolved();
    }

    private function defaultErrorHandler(): callable {
        return fn (\Exception|\Error $e) => throw $e;
    }

    public function __construct(callable $exec = null) {
        if ($exec) {
            Loop::add(function () use ($exec) {
                try {
                    $exec(
                        fn ($v = null) => $this->resolve($v),
                        fn ($e = null) => $this->reject($e)
                    );
                } catch (\Exception|\Error $e) {
                    $this->reject($e);
                }
            });
        }
    }

    public function then(callable $done, callable $error = null): self {
        $handler = [
            'done' => $done,
            'error' => $error ? $error : $this->defaultErrorHandler(),
            'promise' => new Promise()
        ];
        if ($this->isComplete()) {
            $this->processHandler(...$handler);
        } else {
            $this->queuedHandlers[] = $handler;
        }
        return $handler['promise'];
    }

    public function catch(callable $error): self {
        return $this->then(function () {}, $error);
    }

    private function resolve($value = null): void {
        $this->resolved = true;
        $this->resolvedValue = $value;
        $this->processQueue();
    }

    private function reject($error = null): void {
        $this->rejected = true;
        $this->rejectedError = $error;
        if (count($this->queuedHandlers)) {
            $this->processQueue();
        } else {
            ($this->defaultErrorHandler())($error);
        }
    }

    private function processQueue(): void
    {
        foreach ($this->queuedHandlers as $handler) {
            $this->processHandler(...$handler);
        }
        $this->queuedHandlers = [];
    }

    private function processHandler(callable $done, callable $error, Promise $promise): void
    {
        $cb = $this->isResolved() ? $done : $error;
        $cbParam = $this->isResolved() ? $this->resolvedValue : $this->rejectedError;
        try {
            $result = $cb($cbParam);
            if ($result instanceof Promise) {
                $result
                    ->then(fn ($nv) => $promise->resolve($nv))
                    ->catch(fn ($ne) => $promise->reject($ne));
            } else {
                $promise->resolve($result);
            }
        } catch (\Exception|\Error $e) {
            $promise->reject($e);
        }
    }



}
