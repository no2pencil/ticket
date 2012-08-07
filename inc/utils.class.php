<?php
/*
 * utils.class.ph
 * Various utilities used throughout the site
*/
class utils extends framework {
	/*
	 * timestamp();
	 * Returns a timestamp (string)
	*/
	public function timestamp(){
		return date("Y-m-d H:i:s e");
	}
	
	/*
	 * formatPhone(int $phone)
	 * Formats a 10-digit phone number into something more user-friendly
	*/
	public function formatPhone($phone){
		$result = '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 3);
		return $result;
	}
}
?>
