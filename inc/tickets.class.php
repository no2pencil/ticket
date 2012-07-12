<?php
class tickets extends framework {
	public function add($customer, $type, $priority, $dueDate, $status, $specialFields){
		$sql = "INSERT INTO tickets(customer, type, priority, dueDate, status, specialFields, createDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
		if($stmt = parent::get('db')->mysqli()->prepare($sql)){
			$timestamp = parent::get('utils')->timestamp();
			$stmt->bind_param('iisssss', $customer, $type, $priority, $dueDate, $status, $specialFields, $timestamp);
			$stmt->execute();
			$stmt->store_result();
			if($stmt->affected_rows > 0){
				return parent::get('db')->mysqli()->insert_id;
			}
		}
		return false;
	}
	
	public function delete($id){
		$sql = "DELETE FROM tickets WHERE id=" . (int)$id;
		$result = parent::get('db')->mysqli()->query($sql);
		return $result;
	}
	
	public function getTicketById($id){
		$sql = "SELECT t.id AS ID, t.createDate AS 'Created on', u.name AS Creator, c.name as Customer, s.status AS Status, s.description AS 'status_description', t.priority AS Priority, t.dueDate AS 'Due date' FROM tickets AS t " .
					"JOIN statuses AS s ON t.status = s.id " .
					"JOIN users AS u ON t.creator = u.id " .
					"JOIN customers AS c ON t.customer = c.id " . 
						"WHERE t.id=" . (int)$id . " LIMIT 1";
		$result = parent::get('db')->mysqli()->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		return $row;
	}
	
	public function getTypeById($id){
		$types = $this->getTypes(); // laziness ftw
		return $types[$id];
	}
	
	public function getTypes(){
		$sql = "SELECT id, name, description, specialFields FROM ticketTypes";
		$result = parent::get('db')->mysqli()->query($sql);
		$fresult = array();
		while($row = $result->fetch_array()){
			$fresult[$row['id']] = array("name" => $row['name'], "description" => $row['description']);
			$fresult[$row['id']]['specialFields'] = $this->generateSpecialFields($row['specialFields']);
		}
		return $fresult;
	}
	
	public function getBulk($limit, $offset){
		$sql = "SELECT * FROM tickets AS t JOIN customers AS c ON t.customer = c.id LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
		$result = parent::get('db')->mysqli()->query($sql);
		$tickets = array();
		while($row = $result->fetch_array()){
			$tickets[] = $row;
		}
		return $tickets;
	}
	
	public function generateSpecialFields($special){
		if(!empty($special)){
			$result = array();
			$fields = explode(' ;;; ', $special);
			foreach($fields as $id => $field){
				$stuff = explode(' ^^^ ', $field);
				$type = $stuff[0];
				$name = $stuff[1];
				$default = $stuff[2];
				$result[$id]['name'] = $name;
				$result[$id]['default'] = $default;
				$result[$id]['type'] = $type;
				if($type == "textarea"){
					$result[$id]['html'] = '<textarea name="special_' . $type . '_' . $name . '">' . $default . '</textarea>';
				} else {
					$result[$id]['html'] = '<input type="' . $type . '" name="special_' . $type . '_' . $name . '" value="' . $default . '">';
				}
			}
			return $result;
		}
		return false;
	}
}
?>