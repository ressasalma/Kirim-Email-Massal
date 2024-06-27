<?php 
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pengiriman Email</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style type="text/css">
        .custom-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-content {
            padding: 20px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            padding: 20px;
        }

        /* Gaya untuk tabel */
        table {
            width: auto; /* Menghapus width 100% dari tabel */
            border-collapse: collapse;
            white-space: nowrap; /* Menghindari pemutihan teks di dalam sel tabel */
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card custom-card">
                    <div class="card-content">
                        <h1 align="center">History Pengiriman Email</h1>
                        <br><br>
                        <?php
                        // Koneksi ke database
                        include 'koneksi.php';

                        // Query SQL untuk mengambil data history_email
                        $username=$_SESSION['username'];
                        $sqlHistory = "SELECT * FROM history_email WHERE pengirim='$username'";
                        $resultHistory = $conn->query($sqlHistory);

                        if ($resultHistory->num_rows > 0) {
                            echo "<table border='1' class='table-container'>";
                            echo "<tr><th>Nama</th><th>Alamat Email</th><th>Waktu Kirim</th><th>Status</th></tr>";
                            while ($rowHistory = $resultHistory->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $rowHistory['nama'] . "</td>";
                                echo "<td>" . $rowHistory['alamat_email'] . "</td>";
                                echo "<td>" . $rowHistory['waktu_kirim'] . "</td>";
                                echo "<td>" . $rowHistory['status'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "Tidak ada data history pengiriman email.";
                        }

                        // Tutup koneksi ke database
                        $conn->close();
                        ?>
                        <br><br>
                        <a href="index.php" class="btn btn-primary">Back to Index</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
