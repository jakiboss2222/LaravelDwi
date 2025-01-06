<?php
require_once 'db.php';

// Cek apakah request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inisialisasi database
    $db = new Database();

    // Sanitasi input
    $name = $db->sanitize($_POST['name']);
    $phone = $db->sanitize($_POST['phone']);
    $email = $db->sanitize($_POST['email']);
    $message = $db->sanitize($_POST['message']);

    // Validasi input
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($phone)) {
        $errors[] = "Phone is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        // Ganti 'contact_requests' dengan 'contact' jika Anda menggunakan nama tabel tersebut
        $sql = "INSERT INTO contact (nama, phone, email, message) VALUES (?, ?, ?, ?)";
        
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $phone, $email, $message);

        if ($stmt->execute()) {
            // Redirect dengan pesan sukses
            header("Location: index.html?status=success");
            exit();
        } else {
            // Redirect dengan pesan error
            header("Location: index.html?status=error");
            exit();
        }
    } else {
        // Tampilkan error
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    // Redirect jika bukan POST request
    header("Location: index.html");
    exit();
}
?>