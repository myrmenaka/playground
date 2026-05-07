<?php

declare(strict_types=1);

function filterEven(array $numbers): array
{
    $result = [];
    foreach ($numbers as $number) {
        if ($number % 2 !== 0) {
            continue;
        }
        $result[] = $number;
    }
    return $result;
}


print_r(filterEven([1, 2, 3, 4, 5, 6]));