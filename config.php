<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "ukk2025_todolist";
$port = 3308;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>