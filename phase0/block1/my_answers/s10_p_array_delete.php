<?php

$array = [10, 20, 30, 40, 50];

unset($array[2]);
print_r($array);

/*
* array_pop()は戻り値に削除した値を返す
* なので、$arrayに代入してしまうと、削除した値が入り、配列ではなくなる
* 戻り値を捨てるか活かすかは、用途に応じて選択する
*/
array_pop($array);
print_r($array);

$array = array_values($array);
print_r($array);