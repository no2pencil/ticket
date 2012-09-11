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
	 * getAllByTicket(int $invoice)
	 * Retrieves all comments by invoice ID
	*/
        public function getAllByTicket($invoice) {
                $sql = "SELECT comment, dateadded FROM comments where invoice like '%$invoice'";
                $result = parent::get('db')->mysqli()->query($sql);
                if($result) {
			$comments = $result->fetch_array(MYSQLI_ASSOC);
			return $comments;
                }
		return false;
        }


	/*
	 * setComment(str $invoice, str $comment, str $date, int user_id)
	 * Sets the comment for the ticket
	*/
	public function setComment($invoice, $comment, $date, $user_id) {
		$sql = "INSERT INTO comments (
			invoice,
			comment,
			dateadded,
			user_id)
		VALUES(
			'".$invoice."',
			'".$comment."',
			'".$date."',
			$user_id)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
                        $stmt->bind_param('sssi', $invoice, $comment, $date, $user_id);
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
