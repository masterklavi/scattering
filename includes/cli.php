<?php

/**
 * Input from the command line
 * 
 * @param callable $filter Must to return a boolean value
 * @return string
 */
function input(callable $filter = null, $wrong = 'Wrong input!') {
    while (true) {
        $line = readline('>>> ');
        if ($line === false) {
            print PHP_EOL.'Bye!'.PHP_EOL;
            exit;
        }
        $input = strtolower(trim($line));
        if ($input === 'exit') {
            print 'Bye!'.PHP_EOL;
            exit;
        }
        if (is_callable($filter) && !$filter($input)) {
            echo $wrong.PHP_EOL;
        } else {
            break;
        }
    }
    return $input;
}
