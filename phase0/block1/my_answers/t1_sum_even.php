<?php

declare(strict_types=1);

function sumEven(array $numbers): int
{
    $result = 0;
    foreach ($numbers as $number) {
        if ($number % 2 !== 0) {
            continue;
        }
        $result += $number;
    }
    return $result;
}

echo sumEven([1, 2, 3, 4, 5, 6]) . PHP_EOL;
echo sumEven([10, 15, 20, 25, 30]) . PHP_EOL;
