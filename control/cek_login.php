<?php
include_once "connection.php";
session_start();

$url = parse_url($_SERVER['REQUEST_URI']);
$path = explode('/', $url['path']);

if (!isset($_SESSION['login'])) {
  if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = mysqli_escape_string($c, htmlspecialchars($_POST['username']));

    $data = mysqli_fetch_array($c->query("SELECT * FROM users WHERE username='$username'"));

    if ($data != NULL) {
      $hash = $data['password'];
      $password = mysqli_escape_string($c, $_POST['password']);
      if (password_verify($password, $hash)) {
        // Membatalkan akses guru dan wali
        if ($data['level'] == 'guru' || $data['level'] == 'wali') {
          $_SESSION['alert'] = "<script>showDangerToast('Login Gagal! Mohon periksa kembali username atau password')</script>";
          header("Location: ../login.php");
          return;
        }

        if ($data['level'] == 'admin') {
          $_SESSION['nama'] = 'Administrator';
        } else {
          $level = $data['level'];
          $id_user = $data['id_user'];
          if ($level == 'kepsek') {
            $data_user = mysqli_fetch_assoc($c->query("SELECT * FROM guru WHERE id_user='$id_user'"));
            $_SESSION['nama'] = $data_user['nama_guru'];
          } else {
            $data_user = mysqli_fetch_assoc($c->query("SELECT * FROM $level WHERE id_user='$id_user'"));
            $_SESSION['nama'] = $data_user['nama_' . $level];
          }
        }

        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $data['level'];
        $_SESSION['alert'] = "<script>showSuccessToast('Login Berhasil!')</script>";
        header("Location: ../main.php");
      } else {
        $_SESSION['alert'] = "<script>showDangerToast('Login Gagal! Mohon periksa kembali username atau password')</script>";
        header("Location: ../login.php");
      }
    } else {
      $_SESSION['alert'] = "<script>showDangerToast('Login Gagal! Mohon periksa kembali username atau password')</script>";
      header("Location: ../login.php");
    }
  } else {
    if ($path[2] == 'login.php') {
      return;
    }

    $_SESSION['alert'] = "<script>showDangerToast('Login Gagal! Mohon periksa kembali username atau password')</script>";
    header("Location: login.php");
  }
} else {
  if ($path[2] == 'main.php') {
    return;
  }
  header("Location: main.php?p=dashboard");
}
