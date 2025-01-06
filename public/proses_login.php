<?php
session_start(); // Mulai sesi
require_once 'db.php'; // Panggil file Database.php

// Buat objek dari kelas Database
$db = new Database();

// Ambil data dari form
$username = $db->sanitize($_POST['username']);
$password = $db->sanitize($_POST['password']);

// Validasi input
if (empty($username) || empty($password)) {
    die("Nomor HP atau E-mail dan Password harus diisi.");
}

// Cek apakah username dan password cocok di database
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $db->conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Login berhasil, buat sesi
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['username']; // Simpan username di sesi
    $_SESSION['user_id'] = $user['id']; // Simpan user ID di sesi
    header("Location: dashboard.php"); // Redirect ke dashboard
    exit();
} else {
    echo "Username atau password salah!";
}

// Tutup koneksi
$stmt->close();
?>
