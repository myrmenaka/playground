<?php

declare(strict_types=1);

function filterEven(array $numbers): array
{
    $filtered = array_filter($numbers, fn($number) => $number % 2 === 0);
    return array_values($filtered);
}

print_r(filterEven([1, 2, 3, 4, 5, 6]));
print_r(filterEven([10, 15, 20, 25, 30]));