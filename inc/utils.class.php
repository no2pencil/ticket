<?php
/*
 * utils.class.ph
 * Various utilities used throughout the site
*/
class utils extends framework {
	/*
	 * timestamp(boolean $userfriendly=false, int $time=time());
	 * Returns a timestamp. If $time is defined, format will be based of that.
	 * If $userfriendly is true, timestamp will return in "Y-m-d H:i:s e" format
	*/
	public function timestamp($userfriendly=false, $time=-1){
		date_default_timezone_set('EST');
		if($time == -1){
			$time = time();
		}
		if($userfriendly){
			return date("Y-m-d H:i:s e", $time);
		} else {
			return $time;
		}
	}
	
	/*
	 * formatPhone(int $phone)
	 * Formats a 10-digit phone number into something more user-friendly
	*/
        public function formatPhone($phone){
                $result = '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
                return $result;
        }

	/*
	 * formatSearchPhone(int $phone)
	 * formats a 10 digit phone number using only spaces, for the search bar
	*/
        public function formatSearchPhone($phone){
                $result = substr($phone, 0, 3);
                $result .= ' ';
                $result .= substr($phone, 3, 3);
                $result .= ' ';
                $result .= substr($phone, 6, 4);
                return $result;
        }
}
?>
