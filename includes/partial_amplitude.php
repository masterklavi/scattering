<?php

/**
 * Functions to get the partial amplitude
 */

/**
 * Get the partial amplitude from assets
 * 
 * @param double $E The energy
 * @param integer $L The orbital number
 * @param double $I The isospin number
 * @param double $J The full moment
 * @return array complex {Re,Im}
 */
function get_partial_amplitude($E, $L, $I, $J) {
    $l = get_orbital_char($L);
    $table = get_csv_table('./assets/partial_amplitude/'.strtolower($l).'.csv');
    $index = $l;
    $index .= (string)$I;
	$index .= (string)(abs($J)*2);
    $e = round($E/10)*10;
    return [
        $table[$index]['Re'][$e],
        $table[$index]['Im'][$e],
    ];
}

/**
 * Transform the orbital number to alpha
 * 
 * @param integer $L
 * @return string
 */
function get_orbital_char($L) {
	$chars = ['S', 'P', 'D', 'F', 'G', 'H', 'I', 'J'];
	if (isset($chars[$L])) {
		return $chars[$L];
	}
    return false;
}

/**
 * Parse a csv table from assets
 * 
 * @staticvar array $data
 * @param string $filename
 * @return array
 * @throws Exception
 */
function get_csv_table($filename) {
	static $data = [];
	if (!empty($data[$filename])) {
        return $data[$filename];
    }
    if (!file_exists($filename)) {
        throw new Exception("File $filename is not found");
    }
    $table = [];
    $handle = fopen($filename, 'r');
    $first_keys = [];
    foreach (fgetcsv($handle) as $k => $v) {
        if ($k === 0) {
            continue;
        }
        if (!$v) {
            $v = '_';
        }
        $first_keys[$k] = $v;
        if (!isset($table[$v])) {
            $table[$v] = [];
        }
    }
    $second_keys = [];
    foreach (fgetcsv($handle) as $k => $v) {
        if ($k === 0) {
            continue;
        }
        if (!$v) {
            $v = '_';
        }
        $second_keys[$k] = $v;
        $first = $first_keys[$k];
        if (!isset($table[$first][$v])) {
            $table[$first][$v] = [];
        }
    }
    while ($csv = fgetcsv($handle)) {
        $index = 0;
        foreach ($csv as $k => $v) {
            if ($k === 0) {
                $index = $v;
                continue;
            }
            $first = $first_keys[$k];
            $second = $second_keys[$k];
            $table[$first][$second][$index] = $v;
        }
    }
    fclose($handle);
    $data[$filename] = $table;
	return $table;
}
