<?php
class Database {
    private $host = "localhost";
    private $db_name = "my_store";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Tự động tạo database nếu chưa có trên Laragon
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "` CHARACTER SET utf8 COLLATE utf8_general_ci");
            $temp_conn = null;

            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");

            // Tự động tạo bảng account nếu chưa có
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `account` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(255) NOT NULL UNIQUE,
                `fullname` VARCHAR(255) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('admin', 'user') DEFAULT 'user'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3");

            // Tự động thêm các cột mới nếu chưa có
            $columns = [
                "ADD COLUMN `phone` VARCHAR(20) NULL",
                "ADD COLUMN `email` VARCHAR(255) NULL",
                "ADD COLUMN `address` TEXT NULL",
                "ADD COLUMN `remember_token` VARCHAR(255) NULL",
                "ADD COLUMN `reset_token` VARCHAR(255) NULL",
                "ADD COLUMN `reset_token_expire` DATETIME NULL"
            ];
            
            foreach ($columns as $col) {
                try {
                    $this->conn->exec("ALTER TABLE `account` $col");
                } catch(PDOException $e) {
                    // Bỏ qua nếu cột đã tồn tại
                }
            }
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
