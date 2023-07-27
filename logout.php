<?php
session_start();
session_destroy();
session_start();
$_SESSION['alert'] = "<script>showSuccessToast('Logout Berhasil!')</script>";
header("Location: login.php");
