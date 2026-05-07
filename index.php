<?php
header('Content-Type: application/json');
include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // READ: Menampilkan semua data
        $query = mysqli_query($koneksi, "SELECT * FROM users");
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        echo json_encode([
            "status" => "success",
            "data" => $result
        ]);
        break;

    case 'POST':
        // CREATE: Menambah data baru
        // Mengambil input dari Body JSON (Postman) atau Form Data
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = $input['nama'] ?? $_POST['nama'];
        $sandi = $input['sandi'] ?? $_POST['sandi'];

        if ($nama && $sandi) {
            $sql = "INSERT INTO users (nama, sandi) VALUES ('$nama', '$sandi')";
            if (mysqli_query($koneksi, $sql)) {
                echo json_encode(["status" => "success", "message" => "Data berhasil ditambah"]);
            } else {
                echo json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Input nama dan sandi diperlukan"]);
        }
        break;

    case 'DELETE':
        // DELETE: Menghapus data berdasarkan ID di URL (?id=...)
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $sql = "DELETE FROM users WHERE id=$id";
            if (mysqli_query($koneksi, $sql)) {
                echo json_encode(["status" => "success", "message" => "Data ID $id berhasil dihapus"]);
            } else {
                echo json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
        break;
}

mysqli_close($koneksi);
?>
