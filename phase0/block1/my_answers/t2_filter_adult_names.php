<?php

declare(strict_types=1);

function filterAdultNames(array $users): array
{
    $result = [];
    foreach ($users as $user) {
        if ($user['age'] < 20) {
            continue;
        }
        $result[] = $user['name'];
    }
    return $result;
}

$users = [
    ['name' => 'Alice',   'age' => 25],
    ['name' => 'Bob',     'age' => 17],
    ['name' => 'Charlie', 'age' => 30],
    ['name' => 'Diana',   'age' => 19],
    ['name' => 'Eve',     'age' => 22],
];

print_r(filterAdultNames($users));
