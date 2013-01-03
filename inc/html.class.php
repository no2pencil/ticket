<?php
/*
 * html.class.php
 * Used for creating heavy/large HTML elements through single lines of PHP
*/
class html extends framework {
	/*
	 * buildTable(data:array, hide:array(default:empty));
	 * Builds tables based on data provided.
	 * Loops through DATA and displays like so: <tr><td><b>KEY</b></td><td>VALUE</td></tr>
	 * HIDE is used to chose what parts of DATA shouldn't be displayed. This is so two variables
	 * don't need to be used in the calling file (one for buildTable, one for the script). 
	 * HIDE's values should be keys in DATA. Hide's keys do nothing.
	 * if $whitelist is set to true, $hide becomes a whitelist and only keys that are in $hide are shown
	*/
	public function buildTable($data, $hide=array(), $whitelist=false){
		$result = '<table class="table">';
		foreach($data as $key => $value){
			if(($whitelist && in_array($key, $hide)) || (!$whitelist && !in_array($key, $hide))){
				if(is_array($value)){
					$result .= '<tr>';
					foreach($value as $v){
						$result .= '<td>' . $v . '</td>';
					}
					$result .= '</tr>';
				} else {
					$result .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
				}
			}
		}
		$result .= '</table>';
		return $result;
	}
}
?>
