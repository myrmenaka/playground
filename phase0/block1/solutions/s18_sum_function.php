<?php

declare(strict_types=1);

function sum(array $nums): int {
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
    }
    return $total;
}

echo sum([1, 2, 3, 4, 5]) . PHP_EOL;
echo sum([10, 20, 30]) . PHP_EOL;