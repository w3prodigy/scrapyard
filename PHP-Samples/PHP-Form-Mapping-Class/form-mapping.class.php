<?
/**
 *
 * PHP Form Mapping to Database
 * This class allows for quick database inserts using array mapping of form fields to table fields.
 * @author Jay Fortner, 2005
 * 
**/

class db_insert {

	var $status;
	var $map_array;
	var $matching_id;
	var $bad_values = array('\r\n', '\r', '\n'); // values to remove

	/***********************************************
		Clean Mapping Values
	***********************************************/

	function CleanMappingValues() {
		foreach($_POST as $key => $value) {
			$_POST[$key] = str_replace($bad_values, '', $value);
		} // foreach($_POST as $key => $value) 
		$this->map_array = array(
			// table => array( field => post_name )
			'user_account' => array(
				'ua_email' => $_POST['email'],
				'ua_password' => sha1($_POST['password']),
				'ua_creation' => date('Y-m-d')
				),
			'user_account_details' => array(
				'ua_id' => $this->matching_id,
				'uad_name' => $_POST['name'],
				'uad_title' => $_POST['title'],
				'uad_address' => $_POST['address'],
				'uad_city' => $_POST['city'],
				'uad_state' => $_POST['state'],
				'uad_postal' => $_POST['postal'],
				'uad_phone' => $_POST['phone']
				),
			'registration' => array(
				'rm_id' => $_POST['membership'],
				'ua_id' => $_SESSION['account_id'],
				'r_insert' => date('Y-m-d'),
				'r_name' => $_POST['name'],
				'r_dba' => $_POST['dba'],
				'r_description' => $_POST['description'],
				'r_principal' => $_POST['principal'],
				'r_contact' => $_POST['contact'],
				'r_website' => $_POST['website'],
				'r_address' => $_POST['address'],
				'r_city' => $_POST['city'],
				'r_state' => $_POST['state'],
				'r_postal' => $_POST['postal'],
				'r_phone' => $_POST['phone'],
				'r_fax' => $_POST['fax']
				)
			);
		$this->status = 'CleanMappingValues complete';
		return $this->map_array;
	} // function CleanMappingValues

	/***********************************************
		Data Insert
	***********************************************/

	function data_insert($table) {
		$clean_map = $this->CleanMappingValues();
		$sql = "INSERT INTO $table SET ";
		foreach($clean_map[$table] as $field => $value) {
			$sql .= "$field = '$value', ";
		} // foreach($clean_map as $field => $value)
		$sql = rtrim($sql, ', ');
		mysql_query($sql) or die($this->status = "data_insert died on insert");
		$this->matching_id = mysql_insert_id();
		$this->status = 'data_insert complete';
		return $this->matching_id;
	} // function UserAccountCreation

} // class db_insert