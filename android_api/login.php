<?php
include "config.php";

// ambil data dari Android / Postman
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 🔥 DEBUG MODE (LIAT DATA MASUK)
if (isset($_GET['debug'])) {
    echo json_encode([
        "username_diterima" => $username,
        "password_diterima" => $password
    ]);
    exit;
}

// validasi kosong
if ($username == "" || $password == "") {
    echo json_encode([
        "success" => false,
        "message" => "Username atau password kosong"
    ]);
    exit;
}

// ambil user dari database
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    // cek password hash
    if (password_verify($password, $row['password'])) {
        echo json_encode([
            "success" => true,
            "message" => "Login berhasil",
            "data" => [
                "id" => $row['id'],
                "username" => $row['username']
            ]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Password salah"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "User tidak ditemukan"
    ]);
}
?>