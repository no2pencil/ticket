<?php
/*
 * ring_central extends framework
 * Used for Ring Central related functions
*/
class ring_central extends framework {
	/*
	 * get_creds
	 * Returns array containing credentials for ring central's network
	*/
	public function get_creds(){
		$rng_num = parent::get('settings')->get('ringcentral_num');
		$rng_pss = parent::get('settings')->get('ringcentral_pss');
		$rng_frm = parent::get('settings')->get('ringcentral_frm');
		return array('rng_num' => $rng_num, 'rng_pss' => $rng_pass, 'rng_frm' => $rng_frm);
	}
}
?>