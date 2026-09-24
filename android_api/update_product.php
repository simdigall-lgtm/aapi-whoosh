<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $nama_produk = $_POST['nama_produk'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';
    $gambar = $_POST['gambar'] ?? ''; // Ambil data gambar dari Android

    if (empty($id) || empty($nama_produk) || empty($harga) || empty($stok)) {
        echo json_encode(["success" => false, "message" => "Harap lengkapi field wajib"]);
        exit;
    }

    // Cek jika ada upload gambar baru
    if (!empty($gambar)) {
        $nama_file = "prod_" . time() . ".jpg";
        $path = "images/" . $nama_file;
        
        // Buat folder images jika belum ada
        if (!is_dir('images')) {
            mkdir('images', 0777, true);
        }
        
        // Simpan file
        file_put_contents($path, base64_decode($gambar));

        // Update database + kolom gambar
        $stmt = $conn->prepare("UPDATE products SET nama_produk = ?, deskripsi = ?, harga = ?, stok = ?, gambar = ? WHERE id = ?");
        $stmt->bind_param("ssdssi", $nama_produk, $deskripsi, $harga, $stok, $nama_file, $id);
    } else {
        // Update database tanpa ganti gambar
        $stmt = $conn->prepare("UPDATE products SET nama_produk = ?, deskripsi = ?, harga = ?, stok = ? WHERE id = ?");
        $stmt->bind_param("ssdii", $nama_produk, $deskripsi, $harga, $stok, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Produk berhasil diupdate"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal update: " . $conn->error]);
    }
    $stmt->close();
}
?>