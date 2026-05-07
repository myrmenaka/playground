<?php

$array = [];

$array[] = 1;
array_push($array, 2, 3);
$array += [ 3 => 4, 4 => 5];

print_r($array);