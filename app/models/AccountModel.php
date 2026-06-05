<?php
class AccountModel {
    private $conn;
    private $table_name = "account";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountsByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAccountByRememberToken($token) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE remember_token = :token LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAccountByResetToken($token) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE reset_token = :token AND reset_token_expire > NOW() LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user') {
        if ($this->getAccountByUsername($username)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }

    public function updateProfile($username, $fullname, $phone, $email, $address) {
        $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, phone=:phone, email=:email, address=:address WHERE username=:username";
        $stmt = $this->conn->prepare($query);
        
        $fullname = htmlspecialchars(strip_tags($fullname));
        $phone = htmlspecialchars(strip_tags($phone));
        $email = htmlspecialchars(strip_tags($email));
        
        $stmt->bindParam(":fullname", $fullname);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":username", $username);
        
        return $stmt->execute();
    }

    public function updateRememberToken($username, $token) {
        $query = "UPDATE " . $this->table_name . " SET remember_token = :token WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":username", $username);
        return $stmt->execute();
    }

    public function setResetToken($username, $token) {
        // Sử dụng giờ của MySQL để đồng bộ
        $query = "UPDATE " . $this->table_name . " SET reset_token = :token, reset_token_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":username", $username);
        return $stmt->execute();
    }

    public function resetPassword($username, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE " . $this->table_name . " SET password = :password, reset_token = NULL, reset_token_expire = NULL WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $passwordHash);
        $stmt->bindParam(":username", $username);
        return $stmt->execute();
    }
}
?>
