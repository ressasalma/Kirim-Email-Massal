<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
$username=$_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];

    // Pastikan Anda memvalidasi data yang masuk sesuai kebutuhan Anda

    // Misalnya, untuk menghindari SQL Injection, gunakan pernyataan prepared statement

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash kata sandi baru
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Perbarui data pengguna di tabel pengguna (sesuaikan nama tabel dengan yang sesuai)
    $query = "UPDATE user SET username=?, password=? WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $newUsername, $hashedPassword, $username);
    // var_dump($query);
    // die();
    if ($stmt->execute()) {
        // Pembaruan berhasil
        echo "<script language='JavaScript'>
    alert('Data berhasil diperbarui');
    document.location = 'index.php';
    </script>";
    exit;
    } else {
        // Penanganan kesalahan
        $_SESSION['error_message'] = "Gagal memperbarui data pengguna: " . $stmt->error;
        header('Location: index.php'); // Ganti ini dengan halaman yang sesuai
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Alihkan atau tampilkan pesan kesalahan
    $_SESSION['error_message'] = "Gagal memproses permintaan.";
    header('Location: index.php'); // Ganti ini dengan halaman yang sesuai
    exit();
}
?>
