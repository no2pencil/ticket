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
	public function getAllByTicket($invoice){
		$sql = "SELECT * FROM comments AS comment WHERE invoice='"; // unfin
	}


	/*
	 * setComment(str $invoice, str $comment, str $date)
	 * Sets the comment for the ticket
	*/
	public function setComment($invoice, $comment, $date) {
		$sql = "INSERT INTO comment(invoice, comment) VALUES(?,?,?)";
                if($stmt = parent::get('db')->mysqli()->prepare($sql)){
                        //$timestamp = parent::get('utils')->timestamp();
                        $stmt->bind_param('sss', $invoice, $comment, $date);
                        $stmt->execute();
                        $stmt->store_result();
                        if($stmt->affected_rows > 0){
                                return parent::get('db')->mysqli()->insert_id;
                        }
                }
                return false;
	}
}
?>
