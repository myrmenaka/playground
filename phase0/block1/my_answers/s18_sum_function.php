<?php

declare(strict_types=1);

function sum($nums){
    $result = 0;
    foreach ($nums as $value) {
        $result += $value;
    }
    return $result;
}

echo sum([1, 2, 3, 4, 5, "10"]) . PHP_EOL;

echo sum([10, 20, 30]) . PHP_EOL;