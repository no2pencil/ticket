<?php
class SiteSettingsModel extends BaseModel {
	public function getAll(){
		return $this->database->select('site_settings', array());
	}

	public function get($key){
		return $this->database->select('site_settings', array('keyname' => $key));
	}

	public function set($key, $value){
		
	}
}
?>