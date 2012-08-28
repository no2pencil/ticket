<?php
/*
 * comments.class.php
 * Responsible for retrieving and posting comments
*/
class comments {
	/*
	 * getById(int $id)
	 * Retrieves a comment by the ID
	*/
	public function getById($id){
	
	}
	
	/*
	 * getAllByTicket(int $invoice)
	 * Retrieves all comments by invoice ID
	*/
	public function getAllByTicket($invoice){
		$sql = "SELECT * FROM comments AS comment WHERE invoice='"; // unfin
	}
}
?>