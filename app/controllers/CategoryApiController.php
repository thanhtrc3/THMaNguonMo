<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php'); // Import JWT Handler

class CategoryApiController
{
    private $categoryModel;
    private $db;
    private $jwtHandler; // Khai báo

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->jwtHandler = new JWTHandler(); // Khởi tạo
    }

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

    // Lấy danh sách danh mục (Bảo vệ bằng JWT)
    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $categories = $this->categoryModel->getCategories();
            echo json_encode($categories);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Lấy thông tin danh mục (Bảo vệ)
    public function show($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $category = $this->categoryModel->getCategoryById($id);
            if ($category) {
                echo json_encode($category);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Category not found']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Thêm danh mục mới (Bảo vệ)
    public function store()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            
            $result = $this->categoryModel->addCategory($name, $description);
            if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } else {
                http_response_code(201);
                echo json_encode(['message' => 'Category created successfully']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Cập nhật (Bảo vệ)
    public function update($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            
            $result = $this->categoryModel->updateCategory($id, $name, $description);
            if ($result) {
                echo json_encode(['message' => 'Category updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Category update failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Xóa (Bảo vệ)
    public function destroy($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $result = $this->categoryModel->deleteCategory($id);
            if ($result) {
                echo json_encode(['message' => 'Category deleted successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Category deletion failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }
}
?>
