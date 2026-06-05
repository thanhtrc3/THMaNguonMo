<?php
// Bắt đầu session ở đầu file index.php
session_start();

// Kiểm tra Cookie Ghi nhớ đăng nhập
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_user'])) {
    require_once 'app/config/database.php';
    require_once 'app/models/AccountModel.php';
    try {
        $db = (new Database())->getConnection();
        $accountModel = new AccountModel($db);
        $account = $accountModel->getAccountByRememberToken($_COOKIE['remember_user']);
        if ($account) {
            $_SESSION['username'] = $account->username;
            $_SESSION['role'] = $account->role;
        }
    } catch(Exception $e) {}
}

// Tự động xác định BASE_URL dựa trên thư mục hiện tại của dự án
$base_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_dir = rtrim($base_dir, '/');
define('BASE_URL', $base_dir);

require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);


// Khắc phục lỗi số 3: Thay 'DefaultController' bằng 'ProductController' để trang chủ hoạt động
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller not found');
}
require_once 'app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    die('Action not found');
}

call_user_func_array([$controller, $action], array_slice($url, 2));
