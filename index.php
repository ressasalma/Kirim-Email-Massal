<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
 <title>Kirim Email ke Beberapa Alamat Email</title>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    .custom-card {
        border: 1px solid #ccc;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card-content {
    padding: 20px;
  }
   .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background-color: white;
        width: 60%;
        margin: 10% auto;
        padding: 20px;
        border-radius: 5px;
    }

    .show-modal {
        display: block;
    }
    .nav-item.dropdown {
    position: relative;
}

.nav-item.dropdown .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 10rem;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.25rem;
}

.nav-item.dropdown:hover .dropdown-menu {
    display: block;
}

.nav-item.dropdown .dropdown-item {
    display: block;
    width: 100%;
    padding: 0.25rem 1.5rem;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
}

.nav-item.dropdown .dropdown-item:hover,
.nav-item.dropdown .dropdown-item:focus {
    color: #007bff;
    background-color: #f8f9fa;

}
</style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card custom-card">
                    <div class="card-content">
                        <h2 align="center">Kirim Email ke Beberapa Alamat Email</h2>
                       <div class="nav-item dropdown">
                            <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
                                <span>
                                    <div class="d-flex badge-pill">
                                        <span class="fa fa-user mr-2"><b> <?php echo $_SESSION['username']; ?></b></span>
                                        <span class="fa fa-angle-down ml-2"></span>
                                    </div>
                                </span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="account_settings" style="">
                                <a class="dropdown-item" onclick="openManagePopup()" id="manage_account"><i
                                        class="fa fa-cog" ></i> Manage Account</a>
                                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                            </div>
                        </div>
<br><br><br><br>
                        <div class="modal" id="manageModal">
                            <div class="modal-content">
                                <h5 class="modal-title">Update Data User</h5><br><br>
                                <form action="update_user.php" method="post">
                                    <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                                    <div class="form-group">
                                        <label for="newUsername">New Username:</label>
                                        <input type="text" class="form-control" id="newUsername" name="newUsername" value="<?php echo $_SESSION['username']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password:</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                                    </div>
                                    <!-- Add other form fields for additional attributes -->

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                                <button class="btn btn-secondary" onclick="closeManagePopup()">Close</button><br>
                            </div>
                        </div>
                         <div class="modal" id="formatModal">
                            <div class="modal-content">
                                <h5 class="modal-title">Excel Format</h5>
                                <p>Ini adalah format file excel yang akan diupload:</p>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>nama</th>
                                            <th>alamat_email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <form action="import.php" method="post" enctype="multipart/form-data">
                                    <input type="file" name="csvFile" accept=".csv">
                                    <input type="text" name="tableName" placeholder="Table Name">
                                    <input type="submit" value="Import"
                                        style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; text-align: center; text-decoration: none; font-size: 14px; cursor: pointer;">
                                </form><br><br>
                                <button class="btn btn-secondary" onclick="closeFormatPopup()">Close</button><br>
                                <a href="download_format.php" class="btn btn-primary">Download Format as Excel</a><br>

                            </div>
                        </div>

                        <button class="btn btn-primary" onclick="openFormatPopup()">View Excel Format</button>
                        <a href="history.php" class="btn btn-primary" >History</a> 
<br><br>
                        <h2>Show Database Table</h2>
                        <form action="index.php" method="post">
                            <label for="table">Choose a table:</label>
                            <select name="table" id="table">
                                <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Mendapatkan daftar tabel dari database
                            $tablesQuery = "SHOW TABLES";
                            $tablesResult = $conn->query($tablesQuery);

                            if ($tablesResult->num_rows > 0) {
                                while ($tableRow = $tablesResult->fetch_row()) {
                                   $tableName = $tableRow[0];
                                    if ($tableName !== "user" && $tableName !== "history_email") {
                                        echo "<option value='" . $tableName . "'>" . $tableName . "</option>";
                                    } else {

                                    }
                                }
                            }
                            $conn->close();
                            ?>
                            </select>
                            <input type="submit" value="Show Table"
                                style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; text-align: center; text-decoration: none; font-size: 14px; cursor: pointer;">
                        </form><br>
                        <form method="POST" action="kirim.php">
                          <div class="row">
                              <div class="col-md-6">
                                  <table>
                                      <tr>
                                          <td>Subjek :</td>
                                          <td><input type="text" name="subjek" size="30"></td>
                                      </tr>
                                      <tr>
                                          <td>Pesan :</td>
                                          <td><textarea name="pesan" cols="33" rows="15"></textarea></td>
                                      </tr>
                                  </table>
                              </div>
                              <div class="col-md-6">
                                  <h4>Pilih Alamat Email :</h4>
                                  <div style="height: 300px; overflow-y: scroll;">
                                      <?php
                                      // Check if a table is selected and display its content
                                      if (isset($_POST['table'])) {
                                          $selectedTable = $_POST['table'];

                                          // Set selectedTable as a session variable
                                          $_SESSION['selectedTable'] = $selectedTable;

                                          $conn = new mysqli($servername, $username, $password, $dbname);
                                          if ($conn->connect_error) {
                                              die("Connection failed: " . $conn->connect_error);
                                          }

                                          // Retrieve data from the selected table
                                          $query = "SELECT * FROM $selectedTable";
                                          $result = $conn->query($query);

                                          if ($result->num_rows > 0) {
                                              $numRows = $result->num_rows; 
                                              echo "<div='table-container'>";
                                              if ($result->num_rows > 0) {
                                                  while ($row = $result->fetch_assoc()) {
                                                      echo '<label><input type="checkbox" name="email[]" value="' . $row['alamat_email'] . '"> ' . $row['alamat_email'] . '</label><br>';
                                                  }
                                              }

                                              $conn->close();
                                          }
                                      }
                                      ?>
                                  
                                  
                                </div><br>
                                 <input type="checkbox" id="checkSemua"> <label for="checkSemua">Check Semua</label>
                                  <input type="submit" name="kirim" value="Kirim" class="btn btn-primary">
                              </div>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <script>
    // JavaScript untuk memproses tombol "Check Semua"
    document.getElementById("checkSemua").addEventListener("click", function () {
        var checkboxes = document.getElementsByName("email[]");
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    });
     function openFormatPopup() {
        const formatModal = document.getElementById('formatModal');
        formatModal.classList.add('show-modal');
    }

    function closeFormatPopup() {
        const formatModal = document.getElementById('formatModal');
        formatModal.classList.remove('show-modal');
    }
    function openManagePopup() {
        const formatModal = document.getElementById('manageModal');
        formatModal.classList.add('show-modal');
    }

    function closeManagePopup() {
        const formatModal = document.getElementById('manageModal');
        formatModal.classList.remove('show-modal');
    }

</script>
</body>

</html>

