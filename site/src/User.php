<?php

error_reporting( E_ALL );

class User {

	private $id, $username;
	
	protected function __construct($id, $username) {
		$this->id = $id;
		$this->username = $username;
	}
	
	public function get_id() {
		return $this->id;
	}
	
    public static function load() {

        session_start();
        
        if(isset($_SESSION["user"])) {
        	
        	return unserialize($_SESSION["user"]);
        	
        } elseif(isset($_POST["username"]) && isset($_POST["password"])) {
        	
        	$stmt = DataActor::get()->prepare("SELECT id FROM users WHERE name = ? AND password = SHA1(CONCAT(?, users.salt, ?))");
        	$stmt->bindValue(1, $_POST["username"]);
        	$stmt->bindValue(2, $_POST["password"]);
        	$stmt->bindValue(3, Config::get("pepper"));
        	
        	if(!$stmt->execute() || $stmt->rowCount() === 0) {
        		http_response_code(401);
        		return null;
        	}
        	
        	$user = new User($stmt->fetchColumn(0), $_POST["username"]);
        	$_SESSION["user"] = serialize($user);
        	return $user;
        	
        }

        return null;

    }
	
    public function destroy() {
    	session_destroy();
    }
    
    public function __sleep() {
    	return ["id", "username"];
    }
    
}

