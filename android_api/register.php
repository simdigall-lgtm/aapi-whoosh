<?php
include "config.php";

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// cek user
$stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "User sudah ada"]);
    exit;
}

// insert
$stmt = $conn->prepare("INSERT INTO users(username,email,password) VALUES(?,?,?)");
$stmt->bind_param("sss", $username, $email, $hash);

echo json_encode([
    "success" => $stmt->execute(),
    "message" => $stmt->execute() ? "Registrasi berhasil" : "Gagal"
]);
?>