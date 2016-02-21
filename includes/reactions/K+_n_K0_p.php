<?php

/**
 * Formulas of n(K+, K0)p
 * 
 * Formulas of the scattering amplitude of reaction: n(K+, K0)p
 * 
 */


/**
 * The scattering amplitude without spin flip
 * 
 * @param double $E The energy
 * @param double $V The polar angle
 * @return array complex {Re,Im}
 */
function get_amplitude_f($E, $V) {
	$fi1 = get_amplitude_f_i($E, 1, $V);
	$fi0 = get_amplitude_f_i($E, 0, $V);
    $f = [];
	$f[0] = 1/2*($fi1[0]-$fi0[0]);
	$f[1] = 1/2*($fi1[1]-$fi0[1]);
	return $f;
}

/**
 * The scattering amplitude with spin flip
 * 
 * @param double $E The energy
 * @param double $V The polar angle
 * @param double $Phi The azimuthal angle
 * @return array complex {Re,Im}
 */
function get_amplitude_g($E, $V, $Phi) {
	$gi1 = get_amplitude_g_i($E, 1, $V, $Phi);
	$gi0 = get_amplitude_g_i($E, 0, $V, $Phi);
    $g = [];
	$g[0] = 1/2*($gi1[0]-$gi0[0]);
	$g[1] = 1/2*($gi1[1]-$gi0[1]);
	return $g;
}
