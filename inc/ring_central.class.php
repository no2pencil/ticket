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
	public function get_creds() {
		$rng_num = parent::get('settings')->get('rng_num');
		$rng_pss = parent::get('settings')->get('rng_pss');
		$rng_frm = parent::get('settings')->get('rng_frm');
		// Until the settings are working, these will be manually set
		$rng_num = "18666112601";
		$rng_pss = "26272627";
		$rng_frm = "3309292600";
		if(empty($rng_num) || empty($rng_pss) || empty($rng_frm)){
			return false;
		}
		return array('rng_num' => $rng_num, 'rng_pss' => $rng_pss, 'rng_frm' => $rng_frm);
	}
	
	/*
	 * make_url(int $to)
	 * Please note $to isn't actually an int, it's just all numbers.
	 * Creates and returns a URL that, when visited, will do ring central's call thingy
	 * Returns false if get_creds returns false
	*/
	public function make_url($to) {
		$creds = $this->get_creds();
		if(!$creds){
			return false;
		}
		$result = 'https://service.ringcentral.com/ringout.asp?cmd=call&username=';
		$result .= $creds['rng_num'];
		$result .= '&password=' . $creds['rng_pss'];
		$result .= '&to='.$to;
		$result .= '&from=3308187475';
		$result .= '&clid='.$creds['rng_frm'];
		
		return $result;
	}

	/*
	 * log_call(string user, string phone, int id)
	 * Log the call in the database when the 'call' icon is clicked
	 */
	public function log_call($user, $phone, $id) {

        	$time = date("H:i:s");
		$today = date("m-d-Y");
		$comment = $user." initiated call to ".$phone." on invoice ".$id." at ".$time;

		$sql = "INSERT INTO comments ('comment', 'invoice_id', 'dateadded', 'lastupdated', user_id) VALUES('$comment', '$id', '$today', '$time', '')";
		echo json_encode($resp);
	}
}
?>
