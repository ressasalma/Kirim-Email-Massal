<?php
require 'phpspreadsheet/vendor/autoload.php';
include 'koneksi.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

session_start(); // Start the session

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_FILES["csvFile"]["error"] == UPLOAD_ERR_OK) {
        $uploadedFileName = $_FILES["csvFile"]["name"];
        $uploadedFileExtension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);

        $allowedExtensions = array('csv');
        $allowedMimeTypes = array('text/csv', 'application/csv', 'application/vnd.ms-excel');
        $uploadedFileType = $_FILES["csvFile"]["type"];

        if (!in_array($uploadedFileExtension, $allowedExtensions) || !in_array($uploadedFileType, $allowedMimeTypes)) {
            $_SESSION['error_message'] = "Only CSV files are allowed.";
            header('Location: index.php'); // Redirect to index.php
            exit;
        }

        $filePath = $_FILES["csvFile"]["tmp_name"];
        $file = fopen($filePath, "r");

        $tableName = $_POST["tableName"];
        $columns = [];
        $values = [];

        $headerRow = fgetcsv($file, 1000, ",");

        if ($headerRow !== false) {
            $columns = []; // Initialize the $columns array

            foreach ($headerRow as $column) {
                $columns[] = "`$column` TEXT(255)";
                $values[] = "`$column`";
            }

            $createTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (";
            $createTableQuery .= "`{$headerRow[0]}` BIGINST(20) PRIMARY KEY, "; // Menggunakan VARCHAR
            for ($i = 1; $i < count($headerRow); $i++) {
                $createTableQuery .= "`{$headerRow[$i]}` VARCHAR(255), ";
            }
            $createTableQuery = rtrim($createTableQuery, ', '); // Menghapus koma yang tidak diperlukan
            $createTableQuery .= ")";
            // var_dump($createTableQuery);
            // die();
            if ($conn->query($createTableQuery) === FALSE) {
                $_SESSION['error_message'] = "Error creating table: " . $conn->error;
                header('Location: index.php'); // Redirect to index.php
                exit;
            }

            while (($rowData = fgetcsv($file, 1000, ",")) !== false) {
                $phoneNumber = $rowData[0]; // Ambil nomor telepon dari data

                // Filter nomor telepon, jika kosong, lanjutkan ke baris berikutnya
                if (empty($phoneNumber)) {
                    continue;
                }

                $insertQuery = "INSERT IGNORE INTO $tableName (";
                $insertQuery .= implode(', ', $values) . ") VALUES ('";
                $insertQuery .= implode("', '", $rowData) . "')";
                if ($conn->query($insertQuery) === FALSE) {
                    $_SESSION['error_message'] = "Error inserting data: " . $conn->error;
                    header('Location: index.php'); // Redirect to index.php
                    exit;
                }
            }

            fclose($file);

            $_SESSION['success_message'] = "Data imported successfully!";
            header('Location: index.php'); // Redirect to index.php
        } else {
            $_SESSION['error_message'] = "Error reading CSV header row.";
            header('Location: index.php'); // Redirect to index.php
        }
    } else {
        $_SESSION['error_message'] = "Error uploading file.";
        header('Location: index.php'); // Redirect to index.php
    }
}
?>
