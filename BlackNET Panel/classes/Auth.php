<?php
/*
this class is to handle user authentication

how to use
$auth = new Auth
$auth->newLogin($_POST['username'],$_POST['password']);
 */
class Auth extends User
{
    // function to update 2fa secret if needed
    public function updateSecret($username, $secret)
    {
        $pdo = $this->Connect();
        $sql = "UPDATE admin SET secret = :secret WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'secret' => $secret]);
    }

    // check login information with brute force protection
    public function newLogin($username, $password)
    {
        $db = $this->Connect();
        $total_failed_login = 5;
        $lockout_time = 10;
        $account_locked = false;

        $data = $db->prepare('SELECT failed_login, last_login FROM admin WHERE username = (:user) LIMIT 1;');
        $data->bindParam(':user', $username, PDO::PARAM_STR);
        $data->execute();
        $row = $data->fetch();

        if (($data->rowCount() == 1) && ($row->failed_login >= $total_failed_login)) {
            $last_login = strtotime($row->last_login);
            $timeout = $last_login + ($lockout_time * 60);
            $timenow = time();
            if ($timenow < $timeout) {
                $account_locked = true;
                return "Locked";
            }
        }

        $data = $db->prepare('SELECT * FROM admin WHERE username = (:user) AND password = (:password) LIMIT 1;');
        $data->bindParam(':user', $username, PDO::PARAM_STR);
        $data->bindParam(':password', $password, PDO::PARAM_STR);
        $data->execute();
        $row = $data->fetch();

        if (($data->rowCount() == 1) && ($account_locked == false)) {
            $failed_login = $row->failed_login;
            $last_login = $row->last_login;
            $data = $db->prepare('UPDATE admin SET failed_login = "0" WHERE username = (:user) LIMIT 1;');
            $data->bindParam(':user', $username, PDO::PARAM_STR);
            $data->execute();
            return "OK";
        } else {
            sleep(rand(2, 4));
            $data = $db->prepare('UPDATE admin SET failed_login = (failed_login + 1) WHERE username = (:user) LIMIT 1;');
            $data->bindParam(':user', $username, PDO::PARAM_STR);
            $data->execute();
            return "Fail";
        }
        $data = $db->prepare('UPDATE admin SET last_login = now() WHERE username = (:user) LIMIT 1;');
        $data->bindParam(':user', $username, PDO::PARAM_STR);
        $data->execute();
    }

    // Google Recaptcha API to validate recaptcha v2 response
    public function recaptchaResponse($privatekey, $recaptcha_response_field)
    {
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $privatekey . '&response=' . $recaptcha_response_field);
        $responseData = json_decode($verifyResponse);
        return $responseData;
    }

    // check if 2fa is enbaled
    public function isTwoFAEnabled($username)
    {
        $data = $this->getUserData($username);
        return $data->s2fa;
    }

    // Return the user 2fa secret
    public function getSecret($username)
    {
        $data = $this->getUserData($username);
        return $data->secret;
    }
}
