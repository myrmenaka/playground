<?php

declare(strict_types=1);

function add(int $a, int $b): int {
    return $a + $b;
}

echo add(3, 5) . PHP_EOL;
echo add(10, 20) . PHP_EOL;