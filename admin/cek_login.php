<?php
session_start();

include "../config/koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($koneksi, "
SELECT * FROM admin
WHERE username='$username'
AND password='$password'");

if (mysqli_num_rows($query) == 1) {

    $data = mysqli_fetch_assoc($query);

    $_SESSION['login'] = true;
    $_SESSION['nama'] = $data['nama'];

    header("Location: dashboard.php");
    exit;

} else {

    echo "<script>
            alert('Username atau Password salah');
            window.location='login.php';
          </script>";
}