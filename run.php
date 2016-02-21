#!/usr/bin/php
<?php

/**
 * Scattering
 * 
 * Calculation of reaction cross sections of 
 * scattering of particles with nucleons
 * 
 * @version 0.3
 * @author  Master Klavi <masterklavi@gmail.com>
 */

// change current directory
chdir(__DIR__);

// includes
require './includes/cli.php';
require './includes/formulas.php';
require './includes/partial_amplitude.php';
require './includes/reactions/index.php';

// interactive

print PHP_EOL;
print '# Scattering of particles with nucleons'.PHP_EOL;
print PHP_EOL;


print 'Select the reaction (type a number):'.PHP_EOL;
foreach ($reactions as list($number, $title, $filename)) {
    printf('    %2d  %s%s', $number, $title, PHP_EOL);
}
print PHP_EOL;

$reactions_files = [];
foreach ($reactions as list($number, $title, $filename)) {
    $reactions_files[$number] = $filename;
}
$reaction = input(function($input){
    global $reactions_files;
    return isset($reactions_files[$input]);
});
require './includes/reactions/'.$reactions_files[$reaction];

print 'Select the calculation (type a number):'.PHP_EOL;
print '    1   to calculate the differential cross section'.PHP_EOL;
print '    2   to calculate the integral cross section'.PHP_EOL;
print PHP_EOL;
$command = input(function($input){
    return in_array($input, [1, 2, 3]);
});

if ($command == 1):

    print 'Type a value of the polar angle (in radians)'.PHP_EOL;
    $V = input(function($input){
        return $input >= 0 && $input <= M_PI;
    }, 'The polar angle is restricted to the interval [0, π]!');

    print 'Type a value of the azimuthal angle (in radians)'.PHP_EOL;
    $Phi = input(function($input){
        return $input >= 0 && $input < 2*M_PI;
    }, 'The azimuthal angle is restricted to the interval [0, 2π)!');

    print 'Type a value of the energy'.PHP_EOL;
    $E = input(function($input){
        return $input >= 0 && $input <= 1500;
    }, 'The Energy is restricted to the interval [0, 1500]!');

    print 'The differential cross section: ';
    print get_differential_cs($E, $V, $Phi);
    print PHP_EOL;

endif;

if ($command == 2):

    print 'Type a value of the energy'.PHP_EOL;
    $E = input(function($input){
        return $input >= 0 && $input <= 1500;
    }, 'The Energy is restricted to the interval [0, 1500]!');
    
    print 'Type the precision for calculations (0.1, 0.01, etc)'.PHP_EOL;
    $P = input(function($input){
        return $input > 0 && $input <= 0.1;
    }, 'The precision must to be lower than 0.1!');

    print 'The integral cross section: ';
    print get_integral_cs($E, $P);
    print PHP_EOL;

endif;
