<?php

declare(strict_types=1);

echo "=== Pattern 1: \"0\" == false ===\n";
var_dump("0" == false); // true
var_dump("0" === false); // false
    
echo "\n=== Pattern 2: \"abc\" == 0 ===\n";
var_dump("abc" == 0); // false
var_dump("abc" === 0); // false

echo "\n=== Pattern 3: null == false ===\n";
var_dump(null == false); // true
var_dump(null === false); // false

echo "\n=== Pattern 4: [] == false ===\n";
var_dump([] == false); // true
var_dump([] === false); // false

echo "\n=== Pattern 5: \"10\" == 10 ===\n";
var_dump("10" == 10); // true
var_dump("10" === 10); // false
