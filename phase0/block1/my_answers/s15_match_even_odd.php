<?php

$num = 11;

$result = match ($num % 2) {
    0 => '偶数',
    1 => '奇数',
};

echo $result . PHP_EOL;