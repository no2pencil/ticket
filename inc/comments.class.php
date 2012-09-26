<?php
/*
 * comments.class.php
 * Responsible for retrieving and posting comments
*/
class comments extends framework {
	/*
	 * getById(int $id)
	 * Retrieves a comment by the ID
	*/
	public function getById($id){
	
	}
	
        /*
         * getComments(int $invoice_id)
         * Returns all comments for the ticket  with the specified invoice id
         * Uses LIKE for invoice number, see $sql below for more info.
        */
        public function getAllByTicket($invoice_id) {
                $sql = "SELECT comment, dateadded, user_id FROM comments where invoice_id = '$invoice_id'";
                $result = parent::get('db')->mysqli()->query($sql);
		if($result) {
                	$comments = array();  
                	while($row = $result->fetch_array()) {
                        	$comments[] = $row;
			}
			return $comments;
                }
                return false;
        }


	/*
	 * setComment(int $invoice_id, str $comment, str $date, int user_id)
	 * Sets the comment for the ticket
	*/
	public function setComment($invoice_id, $comment, $date, $user_id) {
		$sql = "INSERT INTO comments (
			invoice_id,
			comment,
			dateadded,
			user_id)
		VALUES(
			".$invoice_id.",
			'".$comment."',
			'".$date."',
			$user_id)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
                        $stmt->bind_param('issi', $invoice_id, $comment, $date, $user_id);
                        $stmt->execute();
                        $stmt->store_result();
                        if($stmt->affected_rows > 0){
                                return parent::get('db')->mysqli()->insert_id;
                        }
			return false;
                }
                return false;
	}
}
?>
