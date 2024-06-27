<?php 
require 'koneksi.php';

if (isset($_POST["register"]) ) {

	if (registrasi($_POST) > 0 ) {
		echo "<script>
				alert('user baru berhasil ditambahkan! Silahkan login');document.location='login.php'
				</script>";
	}else {
		echo mysqli_error($conn);
	}
}
 ?>
<html lang="en">

<head>
    <meta charset="UTF-8">


    <link rel="apple-touch-icon" type="image/png"
        href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png">

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="shortcut icon" type="image/x-icon"
        href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico">

    <link rel="mask-icon" type="image/x-icon"
        href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg"
        color="#111">

    <title>CodePen - Modern Login Form</title>

    <link rel="canonical" href="https://codepen.io/donovanperalta/pen/rZNxyM">


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css">

      <style>
    @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Raleway, sans-serif;
    }

    body {
        background: linear-gradient(90deg, #C7C5F4, #776BCC);
        overflow: hidden;
    }

    .container {
        display: flex;
        align-items: center;
        justify-content: center;

    }

    /* style box login */
    .screen {
        background: linear-gradient(90deg, #5D54A4, #7C78B8);
        position: relative;
        height: 600px;
        width: 360px;
        box-shadow: 0px 0px 24px #5C5696;
    }

    .screen__content {
        z-index: 1;
        position: relative;
        height: 100%;
    }

    .screen__background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        -webkit-clip-path: inset(0 0 0 0);
        clip-path: inset(0 0 0 0);
    }

    .screen__background__shape {
        transform: rotate(45deg);
        position: absolute;
    }

    .screen__background__shape1 {
        height: 500px;
        width: 520px;
        background: #FFF;
        top: -50px;
        right: 120px;
        border-radius: 0 72px 0 0;
    }

    .screen__background__shape2 {
        height: 220px;
        width: 220px;
        background: #6C63AC;
        top: -172px;
        right: 0;
        border-radius: 32px;
    }

    .screen__background__shape3 {
        height: 540px;
        width: 190px;
        background: linear-gradient(270deg, #5D54A4, #6A679E);
        top: -24px;
        right: 0;
        border-radius: 32px;
    }

    .screen__background__shape4 {
        height: 400px;
        width: 200px;
        background: #7E7BB9;
        top: 420px;
        right: 50px;
        border-radius: 60px;
    }

    .login {
        width: 320px;
        padding: 30px;
        padding-top: 40px;
    }

    .login__field {
        padding: 12px 0px;
        position: relative;
    }

    .login__icon {
        position: absolute;
        top: 30px;
        color: #7875B5;
    }

    .login__input {
        border: none;
        border-bottom: 2px solid #D1D1D4;
        background: none;
        padding: 10px;
        padding-left: 24px;
        font-weight: 700;
        width: 75%;
        transition: .2s;
    }

    .login__input:active,
    .login__input:focus,
    .login__input:hover {
        outline: none;
        border-bottom-color: #6A679E;
    }

    .login__submit {
        background: #fff;
        font-size: 14px;
        margin-top: 30px;
        padding: 16px 20px;
        border-radius: 26px;
        border: 1px solid #D4D3E8;
        text-transform: uppercase;
        font-weight: 700;
        display: flex;
        align-items: center;
        width: 100%;
        color: #4C489D;
        box-shadow: 0px 2px 2px #5C5696;
        cursor: pointer;
        transition: .2s;
    }

    .login__submit:active,
    .login__submit:focus,
    .login__submit:hover {
        border-color: #6A679E;
        outline: none;
    }

    .button__icon {
        font-size: 24px;
        margin-left: auto;
        color: #7875B5;
    }


    /* Gaya untuk elemen select */
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #D1D1D4;
        border-radius: 5px;
        background: none;
        font-weight: 700;
        transition: .2s;
    }

    /* Gaya untuk elemen select saat digunakan */
    select:active,
    select:focus,
    select:hover {
        outline: none;
        border-color: #6A679E;
    }
    .registration-link {
    display: block;
    margin-top: 20px; /* Adjust the spacing as needed */
    text-align: center;
    text-decoration: none;
    font-weight: 700;
    background-image: linear-gradient(90deg, #6A679E 10%, #FFFFFF 90%);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    transition: color 0.3s ease-in-out;
}

.registration-link:hover {
    color: #6A679E; /* Change the color on hover */
}
    </style>

    <script>
    window.console = window.console || function(t) {};
    </script>



</head>

<body translate="no">
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form action="" method="post" class="login">
    <!-- Form action pointing to proses_login.php -->
			    <div class="login__field">
			        <i class="login__icon fas fa-user"></i>
			        <input type="text" name="username" id="username" class="login__input" placeholder="Username">
			    </div>
			    <div class="login__field">
			        <i class="login__icon fas fa-lock"></i>
			        <input type="password" name="password" id="password" class="login__input" placeholder="Password">
			    </div>
			    <div class="login__field">
			        <i class="login__icon fas fa-lock"></i>
			        <input type="password" name="password2" id="password2" class="login__input" placeholder="Konfirmasi Password">
			    </div>
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" name="nama" id="nama" class="login__input" placeholder="Nama Pengirim">
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" name="email" id="email" class="login__input" placeholder="Email Pengirim">
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" name="password3" id="password3" class="login__input" placeholder="Password PhpMailer">
                </div>
			    <button class="button login__submit" name="register" id="register" type="submit">
			        <span class="button__text">Register Now</span>
			        <i class="button__icon fas fa-chevron-right"></i>
			    </button>
			    <a href="login.php" class="registration-link">Sudah memiliki akun? Login sekarang!</a>
			</form>

            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
</body>

</html>
<?php function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);
    $nama = strtolower(stripslashes($data["nama"]));
    $email = strtolower(stripslashes($data["email"]));
    $password3 = mysqli_real_escape_string($conn, $data["password3"]);

    // Cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username sudah terdaftar!');
                </script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
                </script>";
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan pengguna baru ke database
    $query = "INSERT INTO user (username, password, nama_pengirim, email_pengirim, sandi_phpmailer) VALUES ('$username', '$password', '$nama', '$email', '$password3')";
    // var_dump($query);
    // die();
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        echo "Error: " . mysqli_error($conn);
        return false;
    }
}

?> 