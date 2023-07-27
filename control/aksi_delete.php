<?php
include_once 'connection.php';
include_once 'helper.php';
session_start();

if (isset($_GET['del'])) {
  if ($_GET['del'] == 'guru') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $delUser = delete($c, 'users', "id_user=$id");
    $delGuru = delete($c, 'guru', "id_user=$id");

    if ($delUser && $delGuru) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=guru");
    }
  } elseif ($_GET['del'] == 'siswa') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $delUser = delete($c, 'users', "id_user=$id");
    $delSiswa = delete($c, 'siswa', "id_user=$id");

    if ($delUser && $delSiswa) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=siswa");
    }
  } elseif ($_GET['del'] == 'wali') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $delUser = delete($c, 'users', "id_user=$id");
    $delWali = delete($c, 'wali', "id_user=$id");

    if ($delUser && $delWali) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=wali");
    }
  } elseif ($_GET['del'] == 'rombel') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $del = delete($c, 'rombel', "id_rombel=$id");

    if ($del) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=rombel");
    }
  } elseif ($_GET['del'] == 'mapel') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $del = delete($c, 'mapel', "id_mapel=$id");

    if ($del) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=mapel");
    }
  } elseif ($_GET['del'] == 'pengampu') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $del = delete($c, 'pengampu', "id_pengampu=$id");

    if ($del) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=pengampu");
    }
  } elseif ($_GET['del'] == 'kriteria') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $delKriteria = delete($c, 'kriteria', "id_kriteria=$id");
    $delSub = delete($c, 'subkriteria', "id_kriteria=$id");

    if ($delKriteria && $delSub) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=kriteria");
    }
  } elseif ($_GET['del'] == 'subkriteria') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));
    $id_kriteria = mysqli_escape_string($c, htmlspecialchars($_GET['k']));

    $delSubkriteria = delete($c, 'subkriteria', "id_subkriteria=$id");

    if ($delSubkriteria) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=sub_kriteria&k=$id_kriteria");
    }
  } elseif ($_GET['del'] == 'semester') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $del = delete($c, 'semester', "id_semester=$id");

    if ($del) {
      $_SESSION['alert'] = alert('Data berhasil dihapus', 'success');
      header("Location: ../main.php?p=semester");
    }
  }
} else {
  var_dump('Test');
}
