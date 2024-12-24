<?php
include 'db_connection.php';  // Memastikan file koneksi database sudah benar

// Jika ID ada di URL, ambil data untuk form update
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Mengambil data untuk form berdasarkan ID
    $sql = "SELECT * FROM deliveries WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika data ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer_name = $row['customer_name'];
            $address = $row['address'];
            $phone_number = $row['phone_number'];
            $delivery_time = $row['delivery_time']; // Data waktu pengiriman
            $created_at = $row['created_at']; // Data waktu dibuat
        } else {
            echo "No record found!";
            exit();
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
        exit();
    }
} else {
    echo "Invalid or missing ID!";
    exit();
}

// Jika form di-submit, update data ke database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $address = trim($_POST['address']);
    $phone_number = trim($_POST['phone_number']);
    $delivery_time = trim($_POST['delivery_time']);
    $created_at = trim($_POST['created_at']);

    // Query untuk update data
    $sql = "UPDATE deliveries SET customer_name = ?, address = ?, phone_number = ?, delivery_time = ?, created_at = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssi", $customer_name, $address, $phone_number, $delivery_time, $created_at, $id);

        if ($stmt->execute()) {
            // Setelah update, alihkan kembali ke halaman utama atau halaman pengiriman
            header("Location: delivery.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #ff80ab;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #ff80ab;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #ad1457;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Update Delivery</h1>
    <form method="POST">
        <label for="customer_name">Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>" required>
        
        <label for="address">Address:</label>
        <textarea id="address" name="address" required><?php echo htmlspecialchars($address); ?></textarea>
        
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
        
        <label for="delivery_time">Delivery Time:</label>
        <input type="text" id="delivery_time" name="delivery_time" value="<?php echo htmlspecialchars($delivery_time); ?>" required>
        
        <label for="created_at">Created At:</label>
        <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($created_at); ?>" required>
        
        <button type="submit">Update Delivery</button>
    </form>
</body>
</html>
