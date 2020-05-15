<?php

/*
a class that handles Reset Password Requsts
*/
class ResetPassword extends User
{
	// generate a token
	public function generateToken()
	{
		return sha1(base64_encode(uniqid(rand(), true)));
	}

	// send an email to the user with the password link
	public function sendEmail($username)
	{
		$pdo = $this->Connect();
		$sendmail = new Mailer;
		try {
			if ($this->checkUser($username) != "User Exist") {
				return false;
			} else {
				$token = $this->generateToken();
				$rows = $this->getUserData($username);
				$email = $rows->email;
				$sql = "INSERT INTO confirm_code (username,token) VALUES (:username,:token)";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(['username' => $rows->username, 'token' => $token]);
				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $this->getDir();
				$sendmail->sendmail($email, "Reset password instructions", "
			Hello $rows->username
			<br /><br />
			You recently made a request to reset your BlackNET account password. Please click the link below to continue. 
			<br /><br />
			<a href='" . $actual_link . "reset.php?key=$token'>Update my password.</a>
			<br /><br />
			This link will expire in 10 minutes
			<br /><br />
			If you did not make this request, please ignore this email.");
				return true;
			}
		} catch (Exception $e) {
		}
	}

	public function updatePassword($key, $username, $password)
	{
		$pdo = $this->Connect();
		if (strlen($password) >= 8) {
			$sql = "UPDATE admin SET password = :password WHERE username = :username";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['username' => $username, 'password' => hash("sha256", $this->salt . $password)]);
			$this->deleteToken($key);
			return "Password Has Been Updated";
		} else {
			return 'Please enter more then 8 characters';
		}
	}

	public function getUserAssignToToken($token)
	{
		$pdo = $this->Connect();
		$sql = "SELECT username FROM confirm_code WHERE token = ? limit 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$token]);
		$data = $stmt->fetch();
		return $data;
	}

	public function deleteToken($token)
	{
		try {
			$pdo = $this->Connect();
			$sql = "DELETE FROM confirm_code WHERE token = ?";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$token]);
			return 'Client Removed';
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	public function isExist($key)
	{
		try {
			$pdo = $this->Connect();
			$sql = $pdo->prepare("SELECT * FROM confirm_code WHERE token = :id");
			$sql->execute(['id' => $key]);
			if ($sql->rowCount()) {
				if ($this->isExpired($key) != "Key expired") {
					return "Key Exist";
				} else {
					return "Key expired";
				}
			} else {
				return "Key does not exist";
			}
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	public function isExpired($key)
	{
		try {
			$pdo = $this->Connect();
			$sql = $pdo->prepare("SELECT * FROM confirm_code WHERE token = :id");
			$sql->execute(['id' => $key]);
			$data = $sql->fetch();
			$diff = time() - strtotime($data->created_at);
			if (round($diff / 60) >= 10) {
				$this->deleteToken($key);
				return "Key expired";
			} else {
				return "Key is good";
			}
		} catch (\Throwable $th) {
			//throw $th;
		}
	}


	private function getDir()
	{
		$url = $_SERVER['REQUEST_URI'];
		$parts = explode('/', $url);
		$dir = $_SERVER['SERVER_NAME'];
		for ($i = 0; $i < count($parts) - 1; $i++) {
			$dir .= $parts[$i] . "/";
		}
		return $dir;
	}
}
