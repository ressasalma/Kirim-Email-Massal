<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['selectedTable']) && isset($_SESSION['username'])) {
    $selectedTable = $_SESSION['selectedTable'];
    $username = $_SESSION['username'];
} else {
    echo "Pilih tabel atau username tidak ditemukan";
    exit(); // Keluar dari skrip jika kondisi ini terpenuhi
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "library/PHPMailer.php";
require_once "library/Exception.php";
require_once "library/OAuth.php";
require_once "library/POP3.php";
require_once "library/SMTP.php";

// Inisialisasi pesanInput
$pesanInput = "";

$emailRecipients = $_POST['email']; // Ini akan menjadi array alamat email yang dipilih.

$failedRecipients = []; // Menyimpan daftar alamat email yang gagal terkirim
$successRecipients = []; // Menyimpan daftar alamat email yang berhasil terkirim

foreach ($emailRecipients as $recipientEmail) {
    $mail = new PHPMailer;
 
    //Enable SMTP debugging. 
    $mail->SMTPDebug = 3;                               
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();            
    //Set SMTP host name                          
    $mail->Host = "tls://smtp.gmail.com"; //host mail server
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;   
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Query SQL untuk mengambil informasi dari tabel 'user' berdasarkan username
        $sqll = "SELECT email_pengirim, nama_pengirim, sandi_phpmailer FROM user WHERE username = '$username'";
        // Eksekusi query
        $resultt = $conn->query($sqll);

        // Periksa apakah hasil query mengembalikan baris data
        if ($resultt->num_rows > 0) {
            // Ambil data dari hasil query
            $data = $resultt->fetch_assoc();
            
            $user = $data['email_pengirim'];   //nama-email smtp          
            $password = $data['sandi_phpmailer'];                                  
         
            $from = $data['email_pengirim'];
            $fromName = $data['nama_pengirim']; //nama pengirim
        } else {
            echo "Data pengguna tidak ditemukan";
        }
    } else {
        echo "Username tidak ditemukan";
    }                       
 
    //Provide username and password     
    $mail->Username = $user;   //nama-email smtp          
    $mail->Password = $password;           //password email smtp
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";                           
    //Set TCP port to connect to 
    $mail->Port = 587;                                   
 
    $mail->From = $from; //email pengirim
    $mail->FromName = $fromName;
    
    $sql = "SELECT nama FROM $selectedTable WHERE alamat_email = '$recipientEmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $recipientUsername = $row['nama'];
    } else {
        $recipientUsername = "";
        $failedRecipients[] = ['email' => $recipientEmail, 'nama' => '', 'alasan' => 'Alamat email tidak ada dalam database']; // Menambahkan alamat email yang gagal ke dalam array dengan alasan
        continue; // Lanjut ke alamat email berikutnya
    }

    $mail->addAddress($recipientEmail, '');  //email penerima
    $mail->isHTML(true);
    $pesanInput=$_POST['pesan'];
    $mail->Subject = $_POST['subjek']; //subject
    $mail->Body = 'Hai, '.$recipientUsername.'!' .'<br>'. $pesanInput;  //isi email
    $mail->AltBody = "Email-blash"; //body email (optional)
 
    if ($mail->send()) {
        // Email berhasil terkirim
        $successRecipients[] = $recipientEmail; // Menambahkan alamat email yang berhasil ke dalam array
        $sqlInsert = "INSERT INTO history_email (nama, alamat_email, waktu_kirim, pengirim, status) VALUES ('$recipientUsername', '$recipientEmail', NOW(), '$username', 'Berhasil')";
        if ($conn->query($sqlInsert) !== TRUE) {
            echo "Error: " . $sqlInsert . "<br>" . $conn->error;
        }
    } else {
        $failedRecipients[] = ['email' => $recipientEmail, 'nama' => $recipientUsername, 'alasan' => $mail->ErrorInfo]; // Menambahkan alamat email yang gagal ke dalam array dengan alasan
        $sqlInsert = "INSERT INTO history_email (nama, alamat_email, waktu_kirim, pengirim, status) VALUES ('$recipientUsername', '$recipientEmail', NOW(), '$username', 'Gagal: " . $mail->ErrorInfo . "')";
        if ($conn->query($sqlInsert) !== TRUE) {
            echo "Error: " . $sqlInsert . "<br>" . $conn->error;
        }
    }
}

// Jika ada alamat email yang gagal terkirim, tampilkan dalam tabel
if (!empty($failedRecipients)) {
    echo "<h2>Alamat Email yang Gagal Terkirim:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Alamat Email</th><th>Nama</th><th>Alasan</th></tr>";
    foreach ($failedRecipients as $failedRecipient) {
        echo "<tr>";
        echo "<td>" . $failedRecipient['email'] . "</td>";
        echo "<td>" . $failedRecipient['nama'] . "</td>";
        echo "<td>" . $failedRecipient['alasan'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Jika semua email berhasil terkirim, pindah ke halaman history
if (empty($failedRecipients) && !empty($successRecipients)) {
     echo "<script language='JavaScript'>
    alert('Email berhasil dikirim');
    document.location = 'history.php';
    </script>";
    exit;
}

$conn->close();
?>
