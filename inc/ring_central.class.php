<?php
/*
 * ring_central extends framework
 * Used for Ring Central related functions
*/
class ring_central extends framework {
	/*
	 * get_creds
	 * Returns array containing credentials for ring central's network
	 * Returns false if not all information is filled out
	*/
	public function get_creds(){
		$rng_num = parent::get('settings')->get('ringcentral_num');
		$rng_pss = parent::get('settings')->get('ringcentral_pss');
		$rng_frm = parent::get('settings')->get('ringcentral_frm');
		if(empty($rng_num) || empty($rng_pss) || empty($rng_frm)){
			return false;
		}
		return array('rng_num' => $rng_num, 'rng_pss' => $rng_pass, 'rng_frm' => $rng_frm);
	}
	
	/*
	 * make_url(int $to)
	 * Please note $to isn't actually an int, it's just all numbers.
	 * Creates and returns a URL that, when visited, will do ring central's call thingy
	 * Returns false if get_creds returns false
	*/
	public function make_url($to){
		$creds = $this->get_creds();
		if(!$creds){
			return false;
		}
		$result = 'https://service.ringcentral.com/ringout.asp?cmd=call&username=';
		$result .= $creds['rng_num'] . '&password=' . $creds['rng_pass'] . '&to=';
		$result .= $to;
		
		return $result;
	}
}
?>