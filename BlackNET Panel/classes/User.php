<?php
class User extends Database
{
    // a class that handle user function and settings

    // get user data
    public function getUserData($username)
    {
        $pdo = $this->Connect();
        $sql = "SELECT * FROM admin WHERE 
            username = :username or email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["username" => $username, "email" => $username]);
        $data = $stmt->fetch();
        return $data;
    }

    // count all users
    public function numUsers()
    {
        $pdo = $this->Connect();
        $sql = "SELECT COUNT(*) FROM admin";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchColumn();
        return $data;
    }

    //check if user exist
    public function checkUser($username)
    {
        $pdo = $this->Connect();
        $sql = $pdo->prepare("SELECT * FROM admin WHERE 
            username = :username or email = :email");
        $sql->execute(["username" => $username, "email" => $username]);
        if ($sql->rowCount()) {
            return 'User Exist';
        } else {
            return 'Permission Denied';
        }
    }

    // update a user data
    public function updateUser($id, $oldusername, $username, $email, $password, $auth, $question, $answer, $sqenable)
    {
        $pdo = $this->Connect();

        $user = $this->getUserData($oldusername);

        if ($password == "No change") {
            $password = $user->password;
        } else {
            $password = hash("sha256", $this->salt . $password);
        }

        $sql = "UPDATE admin SET 
        username = :username,
        email = :email,
        password = :password,
        sqenable = :sqenable,
        question = :question,
        answer = :answer
        WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'sqenable' => $sqenable,
            'question' => $question,
            'answer' => $answer, 'id' => $id
        ]);
        return 'Username Updated';
    }

    // Function that enabled 2FA
    public function enables2fa($username, $secret, $auth)
    {
        $pdo = $this->Connect();

        if ($auth == "off") {
            $secret = "null";
        }

        $sql = "UPDATE admin SET 
        s2fa = :auth,
        secret = :secret
        WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'auth' => $auth, 'secret' => $secret]);
    }

    // get question using $username
    public function getQuestionByUser($username)
    {
        $pdo = $this->Connect();
        $sql = "SELECT question,answer,sqenable FROM admin WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $data = $stmt->fetch();
        return $data;
    }

    // check if securiry question enabled
    public function isQuestionEnabled($username)
    {
        try {
            $pdo = $this->Connect();
            $sql = "SELECT sqenable FROM admin WHERE username = :user";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user' => $username]);
            $data = $stmt->fetch();
            if ($data->sqenable == "off") {
                return false;
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
