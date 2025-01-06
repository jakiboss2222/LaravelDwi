<?php
require_once 'db.php'; // Panggil file Database.php

// Buat objek dari kelas Database
$db = new Database();

// Ambil data dari form
$username = $db->sanitize($_POST['username']); // Gunakan metode sanitize
$password = $db->sanitize($_POST['password']);

// Validasi input
if (empty($username) || empty($password)) {
    die("Nomor HP atau E-mail dan Password harus diisi.");
}

// Simpan ke database
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $db->conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Pendaftaran berhasil! <a href='index.html'>Login di sini</a>";
} else {
    echo "Terjadi kesalahan: " . $db->conn->error;
}

// Tutup statement
$stmt->close();
?>
