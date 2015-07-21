<?php
class User {
	public $username;
	public $password;

	public function authenticate() {
		if ($this->password && $this->username) {
			$db = Database::getInstance();
			$sql = "SELECT * FROM users WHERE email=? LIMIT 1";
			$result = $db->queryOne($sql, $this->username);
			if ($this->username === $result['email'] && $this->resolve_password($this->password, $result)) {
				$_SESSION['username'] = $this->username;
				if ($result['type'] === 'admin' || 'instructor' || 'disciple' || 'guest') {
					$_SESSION['userType'] = $result['type'];
				}
				return true;
			}
		}
		return false;
	}

	public function resolve_password($pass, $db_result) {
		$checkon = crypt($pass, $db_result['hash']);
		if ($db_result['password'] === $checkon) {
			return true;
		}
		return false;
	}

	public function getUserId() {
		$db = Database::getInstance();
		$username = User::getUsername();
		$sql = "SELECT id FROM users WHERE username=?";
		$result = $db->queryOne($sql, $username);
		return $result['id'];
	}

	public static function loggedIn() {
		return isset($_SESSION['username']);
	}

	public static function logout() {
		$_SESSION = array();
		session_destroy();
	}
}