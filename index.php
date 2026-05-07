<?php
// Tampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php'; 

// Logika Create & Delete tetap di bawahnya...
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP CRUD Railway</title>
</head>
<body>
    <h2>Tambah Data</h2>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="sandi" name="sandi" placeholder="sandi" required>
        <button type="submit" name="tambah">Simpan</button>
    </form>

    <?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    // Logika Create
    if(isset($_POST['tambah'])){
        $nama = $_POST['nama'];
        $sandi = $_POST['sandi'];
        mysqli_query($koneksi, "INSERT INTO users (nama, sandi) VALUES('$nama', '$sandi')");
    }

    // Logika Delete
    if(isset($_GET['hapus'])){
        $id = $_GET['hapus'];
        mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
        header("Location: index.php");
    }
    ?>

    <h2>Data Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>sandi</th>
            <th>Aksi</th>
        </tr>
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM users");
        while($d = mysqli_fetch_array($data)){
        ?>
        <tr>
            <td><?php echo $d['id']; ?></td>
            <td><?php echo $d['nama']; ?></td>
            <td><?php echo $d['sandi']; ?></td>
            <td>
                <a href="index.php?hapus=<?php echo $d['id']; ?>">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
