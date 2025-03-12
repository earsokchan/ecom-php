<?php
class Database {
    private $pdo;
    
    public function __construct() {
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $dbname = "productDB"; 
        
        try {
            $this->pdo = new PDO(
                "mysql:host=$servername;dbname=$dbname",
                $username,
                $password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Handle product upload (POST)
    public function uploadProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['imageFile']) && isset($_POST['productName']) && 
                isset($_POST['vatNumber']) && isset($_POST['createdDate']) && 
                isset($_POST['price']) && isset($_POST['status'])) {
                
                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($_FILES["imageFile"]["name"]);
                
                if (getimagesize($_FILES["imageFile"]["tmp_name"]) === false) {
                    echo json_encode(['error' => 'File is not an image.']);
                    return;
                }

                if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $targetFile)) {
                    $imageUrl = $targetFile;
                    
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO products (product_name, vat_number, created_date, price, image_url, status) 
                        VALUES (:productName, :vatNumber, :createdDate, :price, :imageUrl, :status)"
                    );
                    
                    $params = [
                        ':productName' => $_POST['productName'],
                        ':vatNumber' => $_POST['vatNumber'],
                        ':createdDate' => $_POST['createdDate'],
                        ':price' => $_POST['price'],
                        ':imageUrl' => $imageUrl,
                        ':status' => $_POST['status']
                    ];
                    
                    try {
                        $stmt->execute($params);
                        echo json_encode(['message' => 'Product uploaded successfully!']);
                    } catch (PDOException $e) {
                        echo json_encode(['error' => 'Error uploading product: ' . $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['error' => 'Error uploading the image.']);
                }
            } else {
                echo json_encode(['error' => 'Missing product data or image.']);
            }
        }
    }

    // Handle product fetch (GET all)
    public function getAllProducts() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
            $stmt = $this->pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($products) {
                echo json_encode($products);
            } else {
                echo json_encode(['error' => 'No products found.']);
            }
        }
    }

    // Handle product fetch by ID (GET)
    public function getProductById() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute([':id' => $_GET['id']]);
            
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                echo json_encode($product);
            } else {
                echo json_encode(['error' => 'Product not found.']);
            }
        }
    }

    // Handle product update (PUT)
    public function updateProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents("php://input"), $_PUT);
            
            if (isset($_GET['id']) && isset($_PUT['productName']) && 
                isset($_PUT['vatNumber']) && isset($_PUT['createdDate']) && 
                isset($_PUT['price']) && isset($_PUT['status'])) {
                
                $params = [
                    ':id' => $_GET['id'],
                    ':productName' => $_PUT['productName'],
                    ':vatNumber' => $_PUT['vatNumber'],
                    ':createdDate' => $_PUT['createdDate'],
                    ':price' => $_PUT['price'],
                    ':status' => $_PUT['status']
                ];
                
                if (isset($_FILES['imageFile'])) {
                    $targetDir = "uploads/";
                    $targetFile = $targetDir . basename($_FILES["imageFile"]["name"]);
                    
                    if (getimagesize($_FILES["imageFile"]["tmp_name"]) === false) {
                        echo json_encode(['error' => 'File is not an image.']);
                        return;
                    }
                    
                    if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $targetFile)) {
                        $params[':imageUrl'] = $targetFile;
                        $sql = "UPDATE products SET product_name = :productName, vat_number = :vatNumber, 
                               created_date = :createdDate, price = :price, status = :status, 
                               image_url = :imageUrl WHERE id = :id";
                    } else {
                        echo json_encode(['error' => 'Error uploading the image.']);
                        return;
                    }
                } else {
                    $sql = "UPDATE products SET product_name = :productName, vat_number = :vatNumber, 
                           created_date = :createdDate, price = :price, status = :status WHERE id = :id";
                }
                
                try {
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($params);
                    echo json_encode(['message' => 'Product updated successfully!']);
                } catch (PDOException $e) {
                    echo json_encode(['error' => 'Error updating product: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => 'Missing product data.']);
            }
        }
    }

    // Handle product delete (DELETE)
    public function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents("php://input"), $_DELETE);
            
            if (isset($_DELETE['id'])) {
                try {
                    $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
                    $stmt->execute([':id' => $_DELETE['id']]);
                    echo json_encode(['message' => 'Product deleted successfully!']);
                } catch (PDOException $e) {
                    echo json_encode(['error' => 'Error deleting product: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => 'Missing product ID.']);
            }
        }
    }
}

// Usage
$db = new Database();

// Route requests to appropriate methods
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db->uploadProduct();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
    $db->getAllProducts();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $db->getProductById();
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $db->updateProduct();
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $db->deleteProduct();
}
?>