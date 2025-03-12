<?php
// Connect to the database
$servername = "localhost";
$username = "root"; // Change this based on your DB username
$password = ""; // Change this based on your DB password
$dbname = "productDB"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle product upload (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imageFile']) && isset($_POST['productName']) && isset($_POST['vatNumber']) && isset($_POST['createdDate']) && isset($_POST['price']) && isset($_POST['status'])) {
        $productName = $_POST['productName'];
        $vatNumber = $_POST['vatNumber'];
        $createdDate = $_POST['createdDate'];
        $price = $_POST['price'];
        $status = $_POST['status'];
        
        // Image handling
        $imageFile = $_FILES['imageFile'];
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($imageFile["name"]);
        
        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (getimagesize($imageFile["tmp_name"]) === false) {
            echo json_encode(['error' => 'File is not an image.']);
            exit;
        }

        // Move the file to the uploads folder
        if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
            $imageUrl = $targetFile;

            // Insert product data into the database
            $sql = "INSERT INTO products (product_name, vat_number, created_date, price, image_url, status) 
                    VALUES ('$productName', '$vatNumber', '$createdDate', '$price', '$imageUrl', '$status')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['message' => 'Product uploaded successfully!']);
            } else {
                echo json_encode(['error' => 'Error uploading product: ' . $conn->error]);
            }
        } else {
            echo json_encode(['error' => 'Error uploading the image.']);
        }
    } else {
        echo json_encode(['error' => 'Missing product data or image.']);
    }
}

// Handle product fetch (GET request)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode(['error' => 'No products found.']);
    }
}

// Handle product fetch by ID (GET request)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Product not found.']);
    }
    exit;
}

// Handle product update (PUT request)
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    if (isset($_GET['id']) && isset($_PUT['productName']) && isset($_PUT['vatNumber']) && isset($_PUT['createdDate']) && isset($_PUT['price']) && isset($_PUT['status'])) {
        $productId = $_GET['id'];
        $productName = $_PUT['productName'];
        $vatNumber = $_PUT['vatNumber'];
        $createdDate = $_PUT['createdDate'];
        $price = $_PUT['price'];
        $status = $_PUT['status'];

        // Check if a new image is provided
        if (isset($_FILES['imageFile'])) {
            $imageFile = $_FILES['imageFile'];
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($imageFile["name"]);
            
            // Check if the file is an image
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (getimagesize($imageFile["tmp_name"]) === false) {
                echo json_encode(['error' => 'File is not an image.']);
                exit;
            }

            // Move the file to the uploads folder
            if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
                $imageUrl = $targetFile;
                $sql = "UPDATE products SET product_name = '$productName', vat_number = '$vatNumber', created_date = '$createdDate', price = '$price', status = '$status', image_url = '$imageUrl' WHERE id = $productId";
            } else {
                echo json_encode(['error' => 'Error uploading the image.']);
                exit;
            }
        } else {
            $sql = "UPDATE products SET product_name = '$productName', vat_number = '$vatNumber', created_date = '$createdDate', price = '$price', status = '$status' WHERE id = $productId";
        }

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Product updated successfully!']);
        } else {
            echo json_encode(['error' => 'Error updating product: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Missing product data.']);
    }
    exit;
}

// Handle product delete (DELETE request)
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    if (isset($_DELETE['id'])) {
        $productId = $_DELETE['id'];
        $sql = "DELETE FROM products WHERE id = $productId";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Product deleted successfully!']);
        } else {
            echo json_encode(['error' => 'Error deleting product: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Missing product ID.']);
    }
    exit;
}

$conn->close();
?> 