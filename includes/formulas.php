<?php

/**
 * Calculates the differential cross section
 * 
 * @param double $E The energy
 * @param double $V The polar angle
 * @param double $Phi The azimuthal angle
 * @return double
 */
function get_differential_cs($E, $V, $Phi) {
    // see the reactions folder
	$f = get_amplitude_f($E, $V);
	$g = get_amplitude_g($E, $V, $Phi);
	return $f[0]*$f[0] + $f[1]*$f[1] + $g[0]*$g[0] + $g[1]*$g[1];
}

/**
 * Calculates the integral cross section
 * 
 * @param double $E The energy
 * @param double $P The precision of calculation
 * @return double
 */
function get_integral_cs($E, $P) {
	$dV = M_PI*$P;
	$dPhi = 2*M_PI*$P;
	$Sigma = 0;
	for ($V = 0; $V < M_PI;) {
		$V_b = $V + $dV;
		for ($Phi = 0; $Phi < 2*M_PI;) {
			$Phi_b = $Phi + $dPhi;
			$fa = get_differential_cs($E, $V, $Phi)*sin($V);
			$fb = get_differential_cs($E, $V_b, $Phi_b)*sin($V_b);
			$Sigma += ($fa+$fb)/2*$dPhi*$dV;
            $Phi = $Phi_b;
		}
        $V = $V_b;
	}
	return $Sigma;
}

/**
 * The scattering amplitude (with isospine) without spin flip
 * 
 * @param double $E The energy
 * @param integer $I The isospin
 * @param double $V The polar angle
 * @return array complex {Re,Im}
 */
function get_amplitude_f_i($E, $I, $V) {
	$fi = [0, 0];
	for ($L = 0; $L <= 7; $L++) {
		$fi1 = get_partial_amplitude($E, $L, $I, $L+1/2);
		$fi2 = get_partial_amplitude($E, $L, $I, $L-1/2);
		$p = get_polynomial_value($L, 0, $V);
		$fi[0] += (($L+1)*$fi1[0]+$L*$fi2[0])*$p;
		$fi[1] += (($L+1)*$fi1[1]+$L*$fi2[1])*$p;
	}
	return $fi;
}

/**
 * The scattering amplitude (with isospine) with spin flip
 * 
 * @param double $E The energy
 * @param integer $I The isospin
 * @param double $V The polar angle
 * @param integer $Phi The azimuthal angle
 * @return array complex {Re,Im}
 */
function get_amplitude_g_i($E, $I, $V, $Phi) {
    $exp = [];
	$exp[0] = cos($Phi);
	$exp[1] = sin($Phi);
    $c = [];
    $gi = [0, 0];
	for ($L = 0; $L <= 7; $L++) {
		$p1 = get_partial_amplitude($E, $L, $I, $L+1/2);
		$p2 = get_partial_amplitude($E, $L, $I, $L-1/2);
		$p = get_polynomial_value($L, 1, $V);
		$c[0] = ($p1[0]-$p2[0])*sin($V)*$p;
		$c[1] = ($p1[1]-$p2[1])*sin($V)*$p;
        
		$gi[0] += $c[0]*$exp[0] - $c[1]*$exp[1];
		$gi[1] += $c[0]*$exp[1] + $c[1]*$exp[0];
	}
	return $gi;
}

/**
 * Ivory–Jacobi formula (Legendre polynomials)
 * 
 * @param integer $L The orbital moment
 * @param integer $M The magnetic moment
 * @param double $V The polar angle
 * @return double
 */
function get_polynomial_value($L, $M, $V) {
    $cos = cos($V);
	if ($M == 0) {
		switch ($L) {
			case 0: return 1;
			case 1: return $cos;
			case 2: return 3/2*$cos*$cos-1/2;
			case 3: return 3/8*$cos*(5*$cos*$cos-3);
			case 4: return 1/8*(35*pow($cos, 4)-30*$cos*$cos+3);
			case 5: return 1/8*$cos*(63*pow($cos, 4)-70*$cos*$cos+15);
			case 6: return 1/16*(231*pow($cos, 6)-315*pow($cos, 4)
                                                            +105*$cos*$cos-5);
			case 7: return 1/16*$cos*(429*pow($cos, 6)-693*pow($cos, 4)
															+315*$cos*$cos-35);
		}
	} elseif ($M == 1) {
		switch ($L) {
			case 0: return 0;
			case 1: return sqrt(1-$cos*$cos);
			case 2: return 3*sqrt(1-$cos*$cos)*$cos;
			case 3: return 3/2*sqrt(1-$cos*$cos)*(5*$cos*$cos-1);
			case 4: return 5/2*sqrt(1-$cos*$cos)*$cos*(7*$cos*$cos-3);
			case 5: return 15/8*sqrt(1-$cos*$cos)*(21*pow($cos, 4)
                                                            -14*$cos*$cos+1);
			case 6: return 21/8*sqrt(1-$cos*$cos)*$cos*(33*pow($cos, 4)
															-30*$cos*$cos+5);
			case 7: return 1/48*sqrt(1-$cos*$cos)*(429*pow($cos, 6)
											-495*pow($cos, 4)+135*$cos*$cos-5);
		}
	}
	return false;
}


