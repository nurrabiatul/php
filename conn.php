<?php
// Mengambil data dari environment variables Railway
$host = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'PBGQeoknwgBAfiaoqrqVnWrXzFuptfpt';
$db   = getenv('MYSQLDATABASE') ?: 'railway';
$port = getenv('MYSQLPORT') ?: 3306;

// Melakukan koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
