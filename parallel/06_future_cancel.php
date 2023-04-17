<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');

$f = $r1->run(fn () => count_to('R1', 100));
var_dump($f->cancelled());
$f->cancel();
var_dump($f->cancelled());

$f = $r1->run(fn () => count_to('R1', 100));
echo "Going to sleep\n";
sleep(2);
$f->cancel();

//$f->cancel();