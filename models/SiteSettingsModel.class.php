<?php
class SiteSettingsModel extends BaseModel {
	/*
	 * getAll()
	 * Returns all settings in an array
	 * Warning: This will just return the rows. It doesn't turn it into an associative array.
	 * TODO: Make associative array option
	*/
	public function getAll(){
		return $this->database->select('site_settings', array());
	}

	public function get($key){
		return $this->database->select('site_settings', array('keyname' => $key));
	}

	/*
	 * set(string $key, string $value, multi $category=-1, multi $helptext=-1)
	 * Sets $key as $value. If category or helptext is specified, they will also be insert/updated along with the key/value pair.
	 * If $category is -1, it will not be updated. Same for helptext. On an insert, they will default to ''.
	 * returns number of rows affected (should be 1 on success, 0 on failure)
	*/
	public function set($key, $value, $category=-1, $helptext=-1){
		$exists = $this->get($key);
		if(empty($exists)){
			$category = ($category==-1) ? '' : $category;
			$helptext = ($helptext==-1) ? '' : $helptext;
			return $this->database->insert('site_settings', array('keyname' => $key, 'keyvalue' => $value, 'category' => $category, 'helptext' => $helptext));
		} else {
			$category = ($category==-1) ? $exists[0]['category'] : $category;
			$helptext = ($helptext==-1) ? $exists[0]['helptext'] : $helptext;
			return $this->database->update('site_settings', array('keyvalue' => $value, 'category' => $category, 'helptext' => $helptext), array('keyname' => $key));
		}
	}
}
?>