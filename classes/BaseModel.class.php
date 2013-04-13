<?php
abstract class BaseModel {
	protected $database;

	public function __construct(){
		$this->database = new Database();
	}
}