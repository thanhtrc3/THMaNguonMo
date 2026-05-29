<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        include_once 'app/views/category/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            $errors = [];
            if (empty($name)) {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } else {
                $categories = $this->categoryModel->getCategories();
                foreach ($categories as $c) {
                    if (strcasecmp($c->getName(), $name) == 0) {
                        $errors[] = 'Danh mục này đã tồn tại.';
                        break;
                    }
                }
            }

            if (empty($errors)) {
                $result = $this->categoryModel->addCategory($name, $description);
                if ($result === true) {
                    header('Location: ' . BASE_URL . '/Category');
                    exit();
                } else {
                    $errors = array_merge($errors, is_array($result) ? $result : ['Lỗi thêm danh mục vào cơ sở dữ liệu.']);
                    include 'app/views/category/add.php';
                }
            } else {
                include 'app/views/category/add.php';
            }
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không thấy danh mục.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            $errors = [];
            if (empty($name)) {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } else {
                $categories = $this->categoryModel->getCategories();
                foreach ($categories as $c) {
                    if ($c->getID() != $id && strcasecmp($c->getName(), $name) == 0) {
                        $errors[] = 'Danh mục này đã tồn tại.';
                        break;
                    }
                }
            }

            if (empty($errors)) {
                $result = $this->categoryModel->updateCategory($id, $name, $description);
                if ($result === true) {
                    header('Location: ' . BASE_URL . '/Category');
                    exit();
                } else {
                    echo "Đã xảy ra lỗi khi lưu danh mục.";
                }
            } else {
                $category = $this->categoryModel->getCategoryById($id);
                include 'app/views/category/edit.php';
            }
        }
    }

    public function delete($id)
    {
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: ' . BASE_URL . '/Category');
            exit();
        } else {
            echo "Đã xảy ra lỗi khi xóa danh mục.";
        }
    }
}
?>
