<?php
include "config.php";

$id = $_POST['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);

echo json_encode([
    "success" => $stmt->execute(),
    "message" => $stmt->execute() ? "Dihapus" : "Gagal"
]);
?>