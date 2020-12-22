<?php

class Loop {
    private static array $callbacks = [];

    public static function add(callable $callback) {
        self::$callbacks[] = $callback;
    }

    public static function run() {
        while (count(self::$callbacks)) {
            self::tick();
        }
    }

    public static function stop() {
        self::$callbacks = [];
    }

    private static function tick() {
        $callbacks = self::$callbacks;
        self::$callbacks = [];

        foreach ($callbacks as $cb) {
            $cb();
        }
    }
}
