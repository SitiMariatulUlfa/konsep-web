<?php
include 'db_connection.php'; // File koneksi database

// Pastikan koneksi database berhasil
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID dari URL untuk dihapus
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Mulai transaksi
        $conn->begin_transaction();

        // Hapus data berdasarkan ID
        $sqlDelete = "DELETE FROM deliveries WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $id);

        if ($stmtDelete->execute()) {
            // Reset ID agar berurutan setelah penghapusan
            $sqlReset1 = "SET @new_id = 0;";
            $sqlReset2 = "UPDATE deliveries SET id = (@new_id := @new_id + 1);";
            $sqlReset3 = "ALTER TABLE deliveries AUTO_INCREMENT = 1;";

            $conn->query($sqlReset1);
            $conn->query($sqlReset2);
            $conn->query($sqlReset3);

            // Commit transaksi
            $conn->commit();

            // Redirect ke halaman daftar dengan pesan sukses
            header("Location: delivery.php?message=Record deleted successfully");
            exit();
        } else {
            throw new Exception("Failed to delete record.");
        }
    } catch (Exception $e) {
        // Rollback jika ada kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        $stmtDelete->close();
    }
} else {
    echo "Invalid or missing ID!";
}

// Tutup koneksi
$conn->close();
?>
