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

    case 'PUT':
        // UPDATE: Mengubah data yang sudah ada berdasarkan ID
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Mengambil data dari body JSON
        $id = $input['id'] ?? null;
        $nama = $input['nama'] ?? null;
        $sandi = $input['sandi'] ?? null;

        if ($id && $nama && $sandi) {
            // Amankan input data
            $id = (int)$id;
            $nama = mysqli_real_escape_string($koneksi, $nama);
            $sandi = mysqli_real_escape_string($koneksi, $sandi);

            $sql = "UPDATE users SET nama='$nama', sandi='$sandi' WHERE id=$id";
            
            if (mysqli_query($koneksi, $sql)) {
                // Cek apakah ada baris yang benar-benar berubah di database
                if (mysqli_affected_rows($koneksi) > 0) {
                    echo json_encode(["status" => "success", "message" => "Data ID $id berhasil diupdate"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Data tidak diupdate (ID tidak ditemukan atau data sama dengan yang lama)"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID, nama, dan sandi harus diisi lengkap di dalam Body JSON"]);
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
