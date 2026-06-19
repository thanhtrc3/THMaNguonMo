<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class ProductController
{
    private $productModel;
    private $db;
    private $uploadDir = 'uploads/'; // Thư mục lưu ảnh

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Tự động tạo thư mục uploads nếu chưa có
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    private function isAdmin() {
        return SessionHelper::isAdmin();
    }

    private function uploadImage($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Validate size (max 2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception("Kích thước ảnh không được vượt quá 2MB.");
        }

        // Validate type (jpg, jpeg, png, gif)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension'] ?? '');
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Chỉ hỗ trợ tải lên các định dạng ảnh: " . implode(', ', $allowedExtensions));
        }

        // Check mime type to prevent malicious files
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception("Tập tin tải lên không phải là ảnh hợp lệ.");
        }

        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $this->uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Có lỗi xảy ra khi lưu tập tin ảnh.");
        }

        return $targetPath;
    }

    private function uploadMultipleImages($files)
    {
        if (!isset($files) || !is_array($files['name'])) {
            return [];
        }

        $uploadedPaths = [];

        foreach ($files['name'] as $index => $name) {
            if ($files['error'][$index] !== UPLOAD_ERR_OK) {
                continue;
            }

            $maxSize = 2 * 1024 * 1024;
            if ($files['size'][$index] > $maxSize) {
                throw new Exception("Kích thước ảnh phụ '" . $name . "' không được vượt quá 2MB.");
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileInfo = pathinfo($name);
            $extension = strtolower($fileInfo['extension'] ?? '');
            if (!in_array($extension, $allowedExtensions)) {
                throw new Exception("Ảnh phụ '" . $name . "' không đúng định dạng. Chỉ hỗ trợ: " . implode(', ', $allowedExtensions));
            }

            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            $mime = mime_content_type($files['tmp_name'][$index]);
            if (!in_array($mime, $allowedMimes)) {
                throw new Exception("Tập tin '" . $name . "' không phải là ảnh hợp lệ.");
            }

            $fileName = time() . '_' . $index . '_' . basename($name);
            $targetPath = $this->uploadDir . $fileName;

            if (!move_uploaded_file($files['tmp_name'][$index], $targetPath)) {
                throw new Exception("Có lỗi xảy ra khi lưu ảnh phụ '" . $name . "'.");
            }

            $uploadedPaths[] = $targetPath;
        }

        return $uploadedPaths;
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/home.php';
    }

    public function list()
    {
        $products = $this->productModel->getProducts();
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/list.php';
    }

    public function manage()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/manage.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $imagePath = '';
            $subImagesPaths = [];

            // Validate
            $errors = [];
            if (empty($name)) {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (strlen($name) < 10 || strlen($name) > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 10 đến 100 ký tự.';
            }

            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            // Xử lý upload ảnh chính
            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $uploadedPath = $this->uploadImage($_FILES['image']);
                    if ($uploadedPath) {
                        $imagePath = $uploadedPath;
                    }
                } elseif (!empty($_POST['image_url'])) {
                    $imagePath = trim($_POST['image_url']);
                }

                // Xử lý upload ảnh phụ
                if (isset($_FILES['sub_images'])) {
                    $subImagesPaths = $this->uploadMultipleImages($_FILES['sub_images']);
                }

                // Xử lý ảnh phụ từ URL
                if (isset($_POST['sub_images_urls']) && is_array($_POST['sub_images_urls'])) {
                    foreach ($_POST['sub_images_urls'] as $url) {
                        $url = trim($url);
                        if (!empty($url)) {
                            if (filter_var($url, FILTER_VALIDATE_URL)) {
                                $subImagesPaths[] = $url;
                            } else {
                                $errors[] = "Đường dẫn ảnh phụ '$url' không phải là URL hợp lệ.";
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }

            if (empty($errors)) {
                $result = $this->productModel->addProduct($name, $description, $price, $category_id, $imagePath, $subImagesPaths);

                if (is_array($result)) {
                    $errors = $result;
                    $categories = (new CategoryModel($this->db))->getCategories();
                    include 'app/views/product/add.php';
                } else {
                    header('Location: ' . BASE_URL . '/Product/manage');
                    exit();
                }
            } else {
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            }
        }
    }

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $deleteSubImageIds = $_POST['delete_sub_images'] ?? [];
            $newSubImagesPaths = [];

            // Validate
            $errors = [];
            if (empty($name)) {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (strlen($name) < 10 || strlen($name) > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 10 đến 100 ký tự.';
            }

            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            // Xử lý ảnh cũ
            $product = $this->productModel->getProductById($id);
            $imagePath = $product ? $product->getImage() : '';

            // Xử lý upload ảnh mới
            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $uploadedPath = $this->uploadImage($_FILES['image']);
                    if ($uploadedPath) {
                        $imagePath = $uploadedPath;
                    }
                } elseif (!empty($_POST['image_url'])) {
                    $imagePath = trim($_POST['image_url']);
                }

                // Xử lý upload ảnh phụ mới
                if (isset($_FILES['sub_images'])) {
                    $newSubImagesPaths = $this->uploadMultipleImages($_FILES['sub_images']);
                }

                // Xử lý ảnh phụ mới từ URL
                if (isset($_POST['sub_images_urls']) && is_array($_POST['sub_images_urls'])) {
                    foreach ($_POST['sub_images_urls'] as $url) {
                        $url = trim($url);
                        if (!empty($url)) {
                            if (filter_var($url, FILTER_VALIDATE_URL)) {
                                $newSubImagesPaths[] = $url;
                            } else {
                                $errors[] = "Đường dẫn ảnh phụ mới '$url' không phải là URL hợp lệ.";
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }

            if (empty($errors)) {
                $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $imagePath, $newSubImagesPaths, $deleteSubImageIds);
                if ($edit) {
                    header('Location: ' . BASE_URL . '/Product/manage');
                    exit();
                } else {
                    $errors[] = "Đã xảy ra lỗi khi lưu sản phẩm.";
                }
            }

            // Reload form on error
            $product = $this->productModel->getProductById($id);
            $categories = (new CategoryModel($this->db))->getCategories();
            include 'app/views/product/edit.php';
        }
    }

    public function delete($id)
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . BASE_URL . '/Product/manage');
            exit();
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    // Shopping Cart Methods
    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: ' . BASE_URL . '/Product');
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $id = $product->getID();
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => 1,
                'image' => $product->getImage()
            ];
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($isAjax) {
            $totalItems = array_sum(array_column($_SESSION['cart'], 'quantity'));
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'totalItems' => $totalItems]);
            exit();
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/Product/list';
        header('Location: ' . $referer);
        exit();
    }

    public function updateCartQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? '';
            $action = $input['action'] ?? '';

            if ($id && isset($_SESSION['cart'][$id])) {
                if ($action === 'increase') {
                    $_SESSION['cart'][$id]['quantity']++;
                } elseif ($action === 'decrease') {
                    if ($_SESSION['cart'][$id]['quantity'] > 1) {
                        $_SESSION['cart'][$id]['quantity']--;
                    }
                } elseif ($action === 'set') {
                    $newQty = isset($input['quantity']) ? (int)$input['quantity'] : 1;
                    if ($newQty >= 1) {
                        $_SESSION['cart'][$id]['quantity'] = $newQty;
                    }
                }
                
                // Calculate new totals
                $itemTotal = $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['quantity'];
                $cartTotal = 0;
                $totalItems = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $cartTotal += $item['price'] * $item['quantity'];
                    $totalItems += $item['quantity'];
                }

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'newQuantity' => $_SESSION['cart'][$id]['quantity'],
                    'itemTotal' => number_format($itemTotal, 0, '.', ',') . ' VNĐ',
                    'cartTotal' => number_format($cartTotal, 0, '.', ',') . ' VNĐ',
                    'totalItems' => $totalItems
                ]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function cart()
    {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/cart.php';
    }

    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . BASE_URL . '/Product/cart');
        exit();
    }

    public function clearCart()
    {
        unset($_SESSION['cart']);
        header('Location: ' . BASE_URL . '/Product/cart');
        exit();
    }

    public function checkout()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: ' . BASE_URL . '/Product/cart');
            exit();
        }

        // Bắt buộc đăng nhập trước khi thanh toán
        if (!SessionHelper::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/account/login');
            exit();
        }

        $accountInfo = null;
        if (SessionHelper::isLoggedIn()) {
            require_once('app/models/AccountModel.php');
            $accountModel = new AccountModel($this->db);
            $accountInfo = $accountModel->getAccountByUsername($_SESSION['username']);
        }

        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $address = $_POST['address'] ?? ''; // This is now the concatenated full string
            $addressJson = $_POST['address_json'] ?? ''; // JSON containing province, district, ward, detail
            $notes = $_POST['notes'] ?? '';

            // Cập nhật thông tin vào DB nếu đã đăng nhập
            if (SessionHelper::isLoggedIn()) {
                require_once('app/models/AccountModel.php');
                $accountModel = new AccountModel($this->db);
                $accountModel->updateProfile($_SESSION['username'], $name, $phone, $email, $addressJson);
                $_SESSION['fullname'] = $name; // Cập nhật lại session fullname
            }
            
            $payment_method = $_POST['payment_method'] ?? 'COD';
            $shipping_method = $_POST['shipping_method'] ?? 'standard';
            $shipping_fee = (int)($_POST['shipping_fee'] ?? 0);
            
            $discount_code = $_POST['discount_code'] ?? '';
            $discount_amount = (int)($_POST['discount_amount'] ?? 0);

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            $cart_subtotal = 0;
            foreach ($_SESSION['cart'] as $item) {
                $cart_subtotal += $item['price'] * $item['quantity'];
            }
            
            $total_amount = $cart_subtotal + $shipping_fee - $discount_amount;
            if($total_amount < 0) $total_amount = 0;

            try {
                $this->db->beginTransaction();

                $query = "INSERT INTO orders (name, phone, email, address, notes, total_amount, payment_method, shipping_method, shipping_fee, discount_code, discount_amount, status) VALUES (:name, :phone, :email, :address, :notes, :total_amount, :payment_method, :shipping_method, :shipping_fee, :discount_code, :discount_amount, 'pending')";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':notes', $notes);
                $stmt->bindParam(':total_amount', $total_amount);
                $stmt->bindParam(':payment_method', $payment_method);
                $stmt->bindParam(':shipping_method', $shipping_method);
                $stmt->bindParam(':shipping_fee', $shipping_fee);
                $stmt->bindParam(':discount_code', $discount_code);
                $stmt->bindParam(':discount_amount', $discount_amount);
                $stmt->execute();

                $order_id = $this->db->lastInsertId();
                $cart = $_SESSION['cart'];

                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                unset($_SESSION['cart']);
                $_SESSION['last_order_id'] = $order_id;
                $_SESSION['last_payment_method'] = $payment_method;
                $_SESSION['last_total_amount'] = $total_amount;
                $_SESSION['last_shipping_fee'] = $shipping_fee;
                $_SESSION['last_discount_amount'] = $discount_amount;
                
                $this->db->commit();

                header('Location: ' . BASE_URL . '/Product/orderConfirmation');
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
?>