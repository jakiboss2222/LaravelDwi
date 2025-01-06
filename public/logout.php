<?php
session_start(); // Mulai sesi
session_destroy(); // Hancurkan semua sesi
header("Location: index.html"); // Redirect ke halaman login
exit();
?>
