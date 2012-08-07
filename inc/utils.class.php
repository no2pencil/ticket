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
                $result = '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
                return $result;
        }

	/*
	 * formats a 10 digit phone number using only spaces, for the search bar
	 *
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
