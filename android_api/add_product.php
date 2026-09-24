<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $nama_produk = $_POST['nama_produk'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';
    $gambar = $_POST['gambar'] ?? ''; // String Base64 dari Android

    if (empty($user_id) || empty($nama_produk) || empty($harga) || empty($stok)) {
        echo json_encode(["success" => false, "message" => "Harap lengkapi field wajib"]);
        exit;
    }

    $nama_file = "";
    if (!empty($gambar)) {
        $nama_file = "prod_" . time() . ".jpg";
        $path = "images/" . $nama_file;
        file_put_contents($path, base64_decode($gambar));
    }

    $stmt = $conn->prepare("INSERT INTO products (user_id, nama_produk, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdis", $user_id, $nama_produk, $deskripsi, $harga, $stok, $nama_file);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Produk berhasil ditambahkan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menambahkan produk"]);
    }
    $stmt->close();
}
?>