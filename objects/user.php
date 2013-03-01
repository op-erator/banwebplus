<?php
require_once(dirname(__FILE__)."/../resources/db_query.php");
require_once(dirname(__FILE__)."/../resources/globals.php");

class user {
	private $name;
	private $access;
	private $exists;

	function __construct($username, $password, $crypt_password) {
		$this->name = $username;
		$this->exists = $this->load_from_db($password, $crypt_password);
		$this->set_accesses();
	}

	public function exists_in_db() {
		return $this->exists;
	}
	
	public function get_name() {
		return $this->name;
	}

	public function get_crypt_password() {
		global $maindb;
		global $userdb;
		if (!$this->exists)
				return '';
		$a_users = db_query("SELECT `pass` FROM `[maindb]`.`[userdb]` WHERE `username`='[username]'", array("maindb"=>$maindb, "userdb"=>$userdb, "username"=>$this->name));
		if ($a_users !== FALSE) {
				if (count($a_users) > 0) {
						$a_user = $a_users[0];
						return $a_user['pass'];
				}
		}
		return '';
	}

	private function set_accesses() {
		if ($this->exists === FALSE)
				return FALSE;
		// todo
	}

	private function load_from_db($password, $crypt_password) {
		global $maindb;
		global $userdb;
		$username = $this->name;
		
		if ($password !== NULL)
				$a_users = db_query("SELECT * FROM `[maindb]`.`[userdb]` WHERE `username`='[username]' AND `pass`=AES_ENCRYPT('[username]','[password]')", array("maindb"=>$maindb, "userdb"=>$userdb, "username"=>$username, "password"=>$password));
		else
				$a_users = db_query("SELECT * FROM `[maindb]`.`[userdb]` WHERE `username`='[username]' AND `pass`='[crypt_password]'", array("maindb"=>$maindb, "userdb"=>$userdb, "username"=>$username, "crypt_password"=>$crypt_password));
		if ($a_users === FALSE)
				return FALSE;
		if (count($a_users) == 0)
				return FALSE;
		return TRUE;
	}
}
?>