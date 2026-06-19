<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/utils/JWTHandler.php');
class AccountController {
    private $accountModel;
    private $db;
    private $jwtHandler;
    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    public function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            $errors = [];

            if (empty($username)) $errors['username'] = "Vui lòng nhập username!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập fullname!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập password!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";

            if (!in_array($role, ['admin', 'user'])) $role = 'user';

            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $result = $this->accountModel->save($username, $fullName, $password, $role);
                if ($result) {
                    // Update the newly created account with the extra info
                    // We format address as JSON to be compatible with the checkout auto-fill logic
                    $addressJson = $address ? json_encode(['address_detail' => $address]) : '';
                    $this->accountModel->updateProfile($username, $fullName, $phone, $email, $addressJson);
                    
                    header('Location: ' . BASE_URL . '/account/login');
                    exit;
                }
            }
        }
    }

    public function logout() {
        SessionHelper::start();
        
        // Xóa token trong DB và xóa cookie
        if (isset($_SESSION['username'])) {
            $this->accountModel->updateRememberToken($_SESSION['username'], NULL);
        }
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, "/");
        }

        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['fullname']);
        header('Location: ' . BASE_URL . '/Product');
        exit;
    }

    public function checkLogin() 
    {
        // Chuyển API sang trả JSON
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->accountModel->getAccountByUserName($username);

        if ($user && password_verify($password, $user->password)) {
            // QUAN TRỌNG: Giữ lại session PHP để không làm hỏng web hiện tại của bạn
            SessionHelper::start();
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['fullname'] = $user->fullname ?? $user->username;

            // Tạo Token JWT
            $token = $this->jwtHandler->encode([
                'id' => $user->id, 
                'username' => $user->username, 
                'fullname' => $user->fullname ?? $user->username,
                'role' => $user->role
            ]);
            
            echo json_encode(['token' => $token, 'success' => true]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }



    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $selected_username = $_POST['username'] ?? '';
            
            if (!empty($selected_username)) {
                // Người dùng đã chọn tài khoản từ danh sách
                $token = bin2hex(random_bytes(16));
                $this->accountModel->setResetToken($selected_username, $token);
                
                $resetLink = BASE_URL . '/account/resetPassword?token=' . $token;
                $success = "Mô phỏng gửi Email thành công! Link khôi phục của bạn là: <br><a href='$resetLink' style='color:#58a6ff; font-weight:bold;'>BẤM VÀO ĐÂY ĐỂ ĐỔI MẬT KHẨU</a>";
            } else {
                $accounts = $this->accountModel->getAccountsByEmail($email);
                
                if (count($accounts) == 1) {
                    // Chỉ có 1 tài khoản, tạo token luôn
                    $token = bin2hex(random_bytes(16));
                    $this->accountModel->setResetToken($accounts[0]->username, $token);
                    
                    $resetLink = BASE_URL . '/account/resetPassword?token=' . $token;
                    $success = "Mô phỏng gửi Email thành công! Link khôi phục của bạn là: <br><a href='$resetLink' style='color:#58a6ff; font-weight:bold;'>BẤM VÀO ĐÂY ĐỂ ĐỔI MẬT KHẨU</a>";
                } elseif (count($accounts) > 1) {
                    // Có nhiều tài khoản, yêu cầu chọn
                    $multiple_accounts = $accounts;
                } else {
                    $error = "Không tìm thấy tài khoản nào đăng ký với Email này!";
                }
            }
        }
        include_once 'app/views/account/forgot_password.php';
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? $_POST['token'] ?? '';
        
        if (empty($token)) {
            echo "Token không hợp lệ.";
            return;
        }
        
        $account = $this->accountModel->getAccountByResetToken($token);
        if (!$account) {
            echo "Link khôi phục đã hết hạn hoặc không hợp lệ.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            
            if (empty($password)) {
                $error = "Vui lòng nhập mật khẩu mới!";
            } elseif ($password != $confirmPassword) {
                $error = "Mật khẩu xác nhận không khớp!";
            } else {
                $this->accountModel->resetPassword($account->username, $password);
                $success = "Đổi mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.";
            }
        }
        include_once 'app/views/account/reset_password.php';
    }
}
?>
