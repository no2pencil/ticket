[1mdiff --git a/inc/tickets.class.php b/inc/tickets.class.php[m
[1mindex c8c44d5..80e9485 100644[m
[1m--- a/inc/tickets.class.php[m
[1m+++ b/inc/tickets.class.php[m
[36m@@ -1,5 +1,11 @@[m
 <?php[m
 class tickets extends framework {[m
[32m+[m
[32m+[m	[32m/*[m
[32m+[m	[32m * add(string $customer, int $type, string $priority, string $dueDate, int $status, string $specialFields)[m
[32m+[m	[32m * Adds a new ticket to the system with the provided information.[m
[32m+[m	[32m * Returns insert ID on success, false on failure[m
[32m+[m	[32m*/[m
 	public function add($customer, $type, $priority, $dueDate, $status, $specialFields){[m
 		$sql = "INSERT INTO tickets(customer, type, priority, dueDate, status, specialFields, createDate) VALUES (?, ?, ?, ?, ?, ?, ?)";[m
 		if($stmt = parent::get('db')->mysqli()->prepare($sql)){[m
[36m@@ -16,8 +22,8 @@[m [mclass tickets extends framework {[m
 [m
 	/* [m
 	 * createType();[m
[31m-	 * Creates a ticket type[m
[31m-	 * TODO: Finish function[m
[32m+[m	[32m * Creates a ticket type.[m
[32m+[m	[32m * Returns true on success, false on failure[m
 	*/[m
 	public function createType() {[m
 		$sql = "INSERT INTO tickettypes(name, description, specialFields) VALUES (?, ?, ?)";[m
[36m@@ -26,25 +32,30 @@[m [mclass tickets extends framework {[m
 			$stmt->execute();[m
 			$stmt->store_result();[m
 			if($stmt->affected_rows > 0) {[m
[32m+[m				[32mreturn true;[m
 			}[m
 		}[m
[32m+[m		[32mreturn false;[m
 	}[m
 	[m
[31m-/*[m
[31m-| createDate    | varchar(30)   | NO   |     | NULL    |                |[m
[31m-| creator       | int(5)        | NO   |     | NULL    |                |[m
[31m-| type          | int(255)      | NO   |     | NULL    |                |[m
[31m-| priority      | varchar(255)  | NO   |     | NULL    |                |[m
[31m-| dueDate       | varchar(10)   | NO   |     | NULL    |                |[m
[31m-| status        | int(255)      | NO   |     | NULL    |                |[m
[31m-| customer      | int(255)      | NO   |     | NULL    |                |[m
[31m-| specialFields | varchar(1000) | NO   |     | NULL    |                |[m
[31m-| invoice       | varchar(12)   | YES  |     | NULL    |                |[m
[31m-*/[m
[32m+[m	[32m/*[m
[32m+[m	[32m| id            | int(255)      | NO   | PRI | NULL    | AUTO_INCREMENT |[m
[32m+[m	[32m| createDate    | varchar(30)   | NO   |     | NULL    |                |[m
[32m+[m	[32m| creator       | int(5)        | NO   |     | NULL    |                |[m
[32m+[m	[32m| type          | int(255)      | NO   |     | NULL    |                |[m
[32m+[m	[32m| priority      | varchar(255)  | NO   |     | NULL    |                |[m
[32m+[m	[32m| dueDate       | varchar(10)   | NO   |     | NULL    |                |[m
[32m+[m	[32m| status        | int(255)      | NO   |     | NULL    |                |[m
[32m+[m	[32m| customer      | int(255)      | NO   |     | NULL    |                |[m
[32m+[m	[32m| specialFields | varchar(1000) | NO   |     | NULL    |                |[m
[32m+[m	[32m| invoice       | varchar(12)   | YES  |     | NULL    |                |[m
[32m+[m	[32m*/[m
 [m
 	/*[m
[32m+[m	[32m * searchTicketById(int $id)[m
 	 * Searches ticket ids by partial name given from ticket name[m
[31m-	 * Returns id, & that is passed to getTicketById[m
[32m+[m	[32m * Returns id, & that is passed to getTicketById[m[41m [m
[32m+[m	[32m * Returns false on failure[m
 	*/[m
 	public function searchTicketById($id) {[m
 		$sql = "SELECT id from tickets where invoice like '%$id%'";[m
[36m@@ -61,17 +72,43 @@[m [mclass tickets extends framework {[m
 	[m
 	/*[m
 	 * search(string $value, string $columns=array('id', 'customer'))[m
[31m-	 * Returns all rows in table "tickets" matching the criteria. Empty array on no results. False on error.[m
[31m-	 * $columns are the columns to search in. Search is done using LIKE[m
[31m-	 * unfin.[m
[32m+[m	[32m * Returns IDs for rows that match search query. Returns false on error.[m
[32m+[m	[32m * $columns are the columns to search in. Search is done using LIKE.[m
 	*/[m
 	public function search($value, $columns=array('id', 'customer')){[m
[31m-		$cols = implode(', ', $columns); // Thank god for prepared statements[m
[31m-		$sql = "SELECT id, createDate, creator, type, priority, dueDate, status, customer, specialFields FROM tickets WHERE";[m
[31m-		foreach($columns as $col){[m
[31m-			// any $columns that contain $value[m
[31m-			$sql .= ' ' . $col . ' LIKE \'%' . $value . '%\''; [m
[32m+[m		[32m$bind = array();[m
[32m+[m		[32m$result = array();[m
[32m+[m		[32m$sql = "SELECT id FROM tickets WHERE";[m
[32m+[m		[32mforeach($columns as $key => $col){[m
[32m+[m			[32mif($key == (count($columns)-1)){[m
[32m+[m				[32m$sql .= ' ' . $col . ' LIKE ?';[m
[32m+[m			[32m} else {[m
[32m+[m				[32m$sql .= ' ' . $col . ' LIKE ? OR';[m
[32m+[m			[32m}[m
[32m+[m			[32m$bind[0] = (empty($bind[0])) ? 's' : $bind[0] . 's';[m
[32m+[m			[32m$bind[] = "%" . $value . "%";[m
[32m+[m		[32m}[m
[32m+[m[41m		[m
[32m+[m		[32mforeach($bind as $key => $value){[m
[32m+[m			[32m$bimp[$key] = &$bind[$key]; // Makes them references for bind_param[m
[32m+[m		[32m}[m
[32m+[m[41m		[m
[32m+[m		[32mif($stmt = parent::get('db')->mysqli()->prepare($sql)){[m
[32m+[m			[32mcall_user_func_array(array($stmt, "bind_param"), $bind); // See what I did there? Dynamic bind_param.[m
[32m+[m			[32m$stmt->execute();[m
[32m+[m			[32m$stmt->bind_result($id);[m
[32m+[m			[32mwhile($stmt->fetch()){[m
[32m+[m				[32m$result[] = array('id' => $id);[m
[32m+[m			[32m}[m
[32m+[m[41m			[m
[32m+[m			[32m$rows = array();[m
[32m+[m			[32mforeach($result as $row){[m
[32m+[m				[32m$row = $this->getTicketById($row['id']);[m
[32m+[m				[32m$rows[] = $row[0];[m
[32m+[m			[32m}[m
[32m+[m			[32mreturn $rows;[m
 		}[m
[32m+[m		[32mreturn false;[m
 	}[m
 [m
 	/*[m
[36m@@ -90,14 +127,21 @@[m [mclass tickets extends framework {[m
 		return $row;[m
 	}[m
 	[m
[32m+[m	[32m/*[m
[32m+[m	[32m * getTypeByID(int $id)[m
[32m+[m	[32m * Returns the type with the $id[m
[32m+[m	[32m*/[m
 	public function getTypeById($id){[m
 		$types = $this->getTypes(); // laziness ftw[m
 		return $types[$id];[m
 	}[m
[31m-[m
[32m+[m[41m	[m
[32m+[m	[32m/*[m
[32m+[m	[32m * getTypes()[m
[32m+[m	[32m * Returns all ticket types[m
[32m+[m	[32m*/[m
 	public function getTypes(){[m
 		$sql = "SELECT id, name FROM tickettypes";[m
[31m-		//$result = parent::get('db')->mysqli()->query($sql);[m
 		$result = parent::get('db')->mysqli()->query($sql);[m
 		$fresult = array();[m
 		while($row = $result->fetch_array()){[m
[36m@@ -107,19 +151,27 @@[m [mclass tickets extends framework {[m
 		return $fresult;[m
 	}[m
 [m
[31m-        public function getStatusById($id) {[m
[31m-                $statuses = $this->getStatuses();[m
[31m-                return $statuses[$id];[m
[31m-        }[m
[32m+[m	[32m/*[m
[32m+[m	[32m * getStatusById(int $id)[m
[32m+[m	[32m * Returns the status with the given ID[m
[32m+[m	[32m*/[m
[32m+[m[32m    public function getStatusById($id) {[m
[32m+[m[32m            $statuses = $this->getStatuses(); // TODO: Benchmark test this vs querying the db[m
[32m+[m[32m            return $statuses[$id];[m
[32m+[m[32m    }[m
 	[m
[32m+[m	[32m/*[m
[32m+[m	[32m * getStatuses()[m
[32m+[m	[32m * Returns all of the statuses[m
[32m+[m	[32m*/[m
 	public function getStatuses() {[m
 		$sql = "SELECT id, status FROM statuses";[m
[31m-                $result = parent::get('db')->mysqli()->query($sql);[m
[31m-                $fresult = array();[m
[31m-                while($row = $result->fetch_array()){[m
[31m-                        $fresult[$row['id']] = array("status" => $row['status']); [m
[31m-                }[m
[31m-                return $fresult;[m
[32m+[m[32m        $result = parent::get('db')->mysqli()->query($sql);[m
[32m+[m[32m        $fresult = array();[m
[32m+[m[32m        while($row = $result->fetch_array()){[m
[32m+[m[32m                $fresult[$row['id']] = array("status" => $row['status']);[m[41m [m
[32m+[m[32m        }[m
[32m+[m[32m        return $fresult;[m
 	}[m
 [m
 	public function getAllOpen() {[m
