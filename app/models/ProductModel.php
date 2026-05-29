<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public $id;
    public $name;
    public $description;
    public $price;
    public $image;
    public $category_id;
    public $category_name;
    public $sub_images = [];

    public function __construct($db = null)
    {
        $this->conn = $db;
    }

    public function getID() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
    public function getCategory() { return $this->category_name; }

    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'ProductModel');
    }

    public function getProductById($id)
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN category c ON p.category_id = c.id 
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ProductModel');
        $product = $stmt->fetch();
        if ($product) {
            $sub_query = "SELECT id, image_path FROM product_images WHERE product_id = :product_id";
            $sub_stmt = $this->conn->prepare($sub_query);
            $sub_stmt->bindParam(':product_id', $product->id, PDO::PARAM_INT);
            $sub_stmt->execute();
            $product->sub_images = $sub_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $product;
    }

    public function addProduct($name, $description, $price, $category_id, $image, $sub_images = [])
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) 
                      VALUES (:name, :description, :price, :category_id, :image)";
            $stmt = $this->conn->prepare($query);

            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));
            $price = htmlspecialchars(strip_tags($price));
            $category_id = htmlspecialchars(strip_tags($category_id));
            $image = htmlspecialchars(strip_tags($image));

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':image', $image);

            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            $product_id = $this->conn->lastInsertId();

            if (!empty($sub_images) && is_array($sub_images)) {
                $sub_query = "INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)";
                $sub_stmt = $this->conn->prepare($sub_query);
                foreach ($sub_images as $path) {
                    $path = htmlspecialchars(strip_tags($path));
                    $sub_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $sub_stmt->bindParam(':image_path', $path);
                    if (!$sub_stmt->execute()) {
                        $this->conn->rollBack();
                        return false;
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image, $new_sub_images = [], $deleted_sub_image_ids = [])
    {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image 
                      WHERE id=:id";
            $stmt = $this->conn->prepare($query);

            $id = htmlspecialchars(strip_tags($id));
            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));
            $price = htmlspecialchars(strip_tags($price));
            $category_id = htmlspecialchars(strip_tags($category_id));
            $image = htmlspecialchars(strip_tags($image));

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':image', $image);

            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            // Handle deleted sub-images
            if (!empty($deleted_sub_image_ids) && is_array($deleted_sub_image_ids)) {
                $placeholders = implode(',', array_fill(0, count($deleted_sub_image_ids), '?'));
                $query_paths = "SELECT image_path FROM product_images WHERE id IN ($placeholders)";
                $stmt_paths = $this->conn->prepare($query_paths);
                $stmt_paths->execute(array_values($deleted_sub_image_ids));
                $paths = $stmt_paths->fetchAll(PDO::FETCH_COLUMN);

                foreach ($paths as $path) {
                    if ($path && !filter_var($path, FILTER_VALIDATE_URL) && file_exists($path)) {
                        unlink($path);
                    }
                }

                $query_del = "DELETE FROM product_images WHERE id IN ($placeholders)";
                $stmt_del = $this->conn->prepare($query_del);
                $stmt_del->execute(array_values($deleted_sub_image_ids));
            }

            // Handle new sub-images
            if (!empty($new_sub_images) && is_array($new_sub_images)) {
                $sub_query = "INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)";
                $sub_stmt = $this->conn->prepare($sub_query);
                foreach ($new_sub_images as $path) {
                    $path = htmlspecialchars(strip_tags($path));
                    $sub_stmt->bindParam(':product_id', $id, PDO::PARAM_INT);
                    $sub_stmt->bindParam(':image_path', $path);
                    if (!$sub_stmt->execute()) {
                        $this->conn->rollBack();
                        return false;
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    public function deleteProduct($id)
    {
        try {
            $this->conn->beginTransaction();

            $product = $this->getProductById($id);
            if ($product) {
                $mainImg = $product->getImage();
                if ($mainImg && !filter_var($mainImg, FILTER_VALIDATE_URL) && file_exists($mainImg)) {
                    unlink($mainImg);
                }

                if (!empty($product->sub_images)) {
                    foreach ($product->sub_images as $subImg) {
                        $subPath = $subImg['image_path'];
                        if ($subPath && !filter_var($subPath, FILTER_VALIDATE_URL) && file_exists($subPath)) {
                            unlink($subPath);
                        }
                    }
                }
            }

            $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return false;
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }
}
?>