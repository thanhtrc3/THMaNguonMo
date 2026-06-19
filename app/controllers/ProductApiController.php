<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php'); // Import JWT Handler

class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler; // Khai báo

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler(); // Khởi tạo
    }

    // Hàm xác thực token từ Request Header
    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }

    // Lấy danh sách sản phẩm (Bảo vệ bằng JWT)
    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $products = $this->productModel->getProducts();
            echo json_encode($products);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - Token không hợp lệ']);
        }
    }

    // Lấy thông tin sản phẩm theo ID (Bảo vệ bằng JWT)
    public function show($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $product = $this->productModel->getProductById($id);
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Product not found']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Thêm sản phẩm mới (Bảo vệ bằng JWT)
    public function store()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (json_last_error() !== JSON_ERROR_NONE || $data === null) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid JSON payload']);
                return;
            }
            
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $image = $data['image'] ?? ''; 
            
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image, []);
            
            if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } else if ($result) {
                http_response_code(201);
                echo json_encode(['message' => 'Product created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Product creation failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Cập nhật sản phẩm theo ID (Bảo vệ bằng JWT)
    public function update($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (json_last_error() !== JSON_ERROR_NONE || $data === null) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid JSON payload']);
                return;
            }
            
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            
            $currentProduct = $this->productModel->getProductById($id);
            if (!$currentProduct) {
                 http_response_code(404);
                 echo json_encode(['message' => 'Product not found']);
                 return;
            }

            $image = $data['image'] ?? $currentProduct->image;
            
            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image, [], []);
            
            if ($result) {
                echo json_encode(['message' => 'Product updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product update failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Xóa sản phẩm theo ID (Bảo vệ bằng JWT)
    public function destroy($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $result = $this->productModel->deleteProduct($id);
            
            if ($result) {
                echo json_encode(['message' => 'Product deleted successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product deletion failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }
}
?>
