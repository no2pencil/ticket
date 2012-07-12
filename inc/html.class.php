<?php
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
		$result = '<table>';
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
	
	private function buildTable_recursiveArrays($array){
		
	}
	
	/*
	 * buildForm(action:string, method:string, data:array)
	 * Builds a form based on data provided
	 * Acts much like buildTable. Loops through data and creates inputs based on them.
	 * Data's keys are the displayed name, value should be an array(name:string, type:string, value:string)
	*/
	public function buildForm($action, $method, $data){
		$result = '<table>';
		foreach($data as $key => $value){
			$result .= '<tr><td>' . $key . '</td><td><input type="' . $data[1] . '" name="' . $data[0] . '" value="' . $data[2] . '"></td></tr>';
		}
		$result .= '</table>';
	}
}
?>