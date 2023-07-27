<?php
include_once 'connection.php';
include_once 'helper.php';
session_start();

if (isset($_POST['guru'])) {
  $status = mysqli_escape_string($c, htmlspecialchars($_POST['status']));
  $username = mysqli_escape_string($c, htmlspecialchars($_POST['username']));

  if ($status == 'asn') {
    $nip = $username;
  } else {
    $nip = '';
  }

  $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
  $data_user = array(
    'username' => $username,
    'password' => password_hash($_POST['username'], PASSWORD_DEFAULT),
    'level' => 'guru'
  );

  $query_user = update($c, $data_user, 'users', "id_user=$id_user");

  $data_guru = array(
    'nip' => $nip,
    'nama_guru' => mysqli_escape_string($c, htmlspecialchars($_POST['nama'])),
    'pendidikan' => mysqli_escape_string($c, htmlspecialchars($_POST['pendidikan'])),
    'email' => mysqli_escape_string($c, htmlspecialchars($_POST['email'])),
    'nohp' => mysqli_escape_string($c, htmlspecialchars($_POST['nohp']))
  );

  $query_guru = update($c, $data_guru, 'guru', "id_user=$id_user");

  if ($query_guru && $query_user) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=guru");
  }
} elseif (isset($_POST['siswa'])) {
  $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
  $nisn = mysqli_escape_string($c, htmlspecialchars($_POST['nisn']));

  $data_user = array(
    'username' => $nisn
  );

  $query_user = update($c, $data_user, 'users', "id_user=$id_user");

  $data_siswa = array(
    'id_rombel' => mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel'])),
    'id_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['id_wali'])),
    'nama_siswa' => mysqli_escape_string($c, htmlspecialchars($_POST['nama'])),
    'nisn' => $nisn,
    'email_siswa' => mysqli_escape_string($c, htmlspecialchars($_POST['email'])),
    'nohp_siswa' => mysqli_escape_string($c, htmlspecialchars($_POST['nohp']))
  );

  $query_siswa = update($c, $data_siswa, 'siswa', "id_user=$id_user");

  if ($query_siswa) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=siswa");
  }
} elseif (isset($_POST['wali'])) {
  $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));

  $data_user = array(
    'username' => mysqli_escape_string($c, htmlspecialchars($_POST['username']))
  );

  $query_user = update($c, $data_user, 'users', "id_user=$id_user");

  $data_wali = array(
    'nama_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['nama'])),
    'email_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['email'])),
    'nohp_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['nohp']))
  );

  $query_wali = update($c, $data_wali, 'wali', "id_user=$id_user");

  if ($query_wali) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=wali");
  }
} elseif (isset($_POST['rombel'])) {
  $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel']));

  $data = array(
    'walas' => mysqli_escape_string($c, htmlspecialchars($_POST['walas'])),
    'nama_rombel' => mysqli_escape_string($c, htmlspecialchars($_POST['nama']))
  );

  $query = update($c, $data, 'rombel', "id_rombel=$id_rombel");

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=rombel");
  }
} elseif (isset($_POST['mapel'])) {
  $id_mapel = mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel']));

  $data = array(
    'nama_mapel' => mysqli_escape_string($c, htmlspecialchars($_POST['nama']))
  );

  $query = update($c, $data, 'mapel', "id_mapel=$id_mapel");

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=mapel");
  }
} elseif (isset($_POST['pengampu'])) {
  $id_pengampu = mysqli_escape_string($c, htmlspecialchars($_POST['id_pengampu']));

  $data = array(
    'id_mapel' => mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel'])),
    'id_rombel' => mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel'])),
    'id_guru' => mysqli_escape_string($c, htmlspecialchars($_POST['id_guru']))
  );

  $query = update($c, $data, 'pengampu', "id_pengampu=$id_pengampu");

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=pengampu");
  }
} elseif (isset($_POST['kriteria'])) {
  $id_kriteria = mysqli_escape_string($c, htmlspecialchars($_POST['id_kriteria']));

  $data = array(
    'kode_kriteria' => mysqli_escape_string($c, htmlspecialchars($_POST['kode_kriteria'])),
    'nama_kriteria' => mysqli_escape_string($c, htmlspecialchars($_POST['nama_kriteria'])),
    'bobot' => mysqli_escape_string($c, htmlspecialchars($_POST['bobot']))
  );

  $query = update($c, $data, 'kriteria', "id_kriteria=$id_kriteria");

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=kriteria");
  }
} elseif (isset($_POST['subkriteria'])) {
  $id_subkriteria = mysqli_escape_string($c, htmlspecialchars($_POST['id_subkriteria']));
  $id_kriteria = mysqli_escape_string($c, htmlspecialchars($_POST['id_kriteria']));

  $data = array(
    'nama_sub' => mysqli_escape_string($c, htmlspecialchars($_POST['nama_sub'])),
    'bobot_sub' => mysqli_escape_string($c, htmlspecialchars($_POST['bobot_sub']))
  );

  $query = update($c, $data, 'subkriteria', "id_subkriteria=$id_subkriteria");

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=sub_kriteria&k=$id_kriteria");
  }
} elseif (isset($_POST['update_kepsek'])) {
  $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
  $nama_guru = mysqli_escape_string($c, htmlspecialchars($_POST['nama_guru']));
  $username = mysqli_escape_string($c, htmlspecialchars($_POST['username']));
  $nip = mysqli_escape_string($c, htmlspecialchars($_POST['nip']));
  $pendidikan = mysqli_escape_string($c, htmlspecialchars($_POST['pendidikan']));
  $email = mysqli_escape_string($c, htmlspecialchars($_POST['email']));
  $nohp = mysqli_escape_string($c, htmlspecialchars($_POST['nohp']));

  $dataGuru = [
    'nip' => $nip,
    'nama_guru' => $nama_guru,
    'pendidikan' => $pendidikan,
    'email' => $email,
    'nohp' => $nohp
  ];

  $dataUser = [
    'username' => $username
  ];

  $queryUser = update($c, $dataUser, 'users', "id_user=$id_user");
  $queryGuru = update($c, $dataGuru, 'guru', "id_user=$id_user");

  if ($queryUser && $queryGuru) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=kepsek");
  }
} elseif (isset($_POST['update_password'])) {
  $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user_for_password']));
  $page = mysqli_escape_string($c, htmlspecialchars($_POST['page']));
  $password = mysqli_escape_string($c, htmlspecialchars($_POST['password']));
  $conf_password = mysqli_escape_string($c, htmlspecialchars($_POST['conf_password']));

  if ($password != $conf_password) {
    $_SESSION['alert'] = alert('Password tidak sama', 'warning');
    header("Location: ../main.php?p=" . $page);
    return;
  }

  $dataPassword = [
    'password' => password_hash($password, PASSWORD_DEFAULT)
  ];

  $queryUpdate = update($c, $dataPassword, 'users', "id_user=$id_user");

  if ($queryUpdate) {
    $_SESSION['alert'] = alert('Password berhasil diupdate', 'success');
    header("Location: ../main.php?p=" . $page);
  }
} elseif (isset($_POST['update_password_not_login'])) {
  $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
  $password = mysqli_escape_string($c, htmlspecialchars($_POST['password']));
  $conf_password = mysqli_escape_string($c, htmlspecialchars($_POST['conf_password']));

  if ($password != $conf_password) {
    $_SESSION['alert'] = alert('Password tidak sama. Silahkan ulangi kembali', 'warning');
    header("Location: ../forgot.php");
    return;
  }

  $dataPassword = [
    'password' => password_hash($password, PASSWORD_DEFAULT)
  ];

  $queryUpdate = update($c, $dataPassword, 'users', "id_user=$id_user");

  if ($queryUpdate) {
    $_SESSION['alert'] = alert('Password berhasil diupdate. Silahkan login dengan password yang telah direset', 'success');
    header("Location: ../login.php");
  }
} elseif (isset($_POST['semester'])) {
  $id_semester = mysqli_escape_string($c, htmlspecialchars($_POST['id_semester']));

  $data = array(
    'nama_semester' => mysqli_escape_string($c, htmlspecialchars($_POST['nama_semester'])),
    'tahun' => mysqli_escape_string($c, htmlspecialchars($_POST['tahun']))
  );

  $query = update($c, $data, 'semester', "id_semester=$id_semester");

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=semester");
  }
}

if (isset($_GET['update'])) {
  if ($_GET['update'] == 'semester') {
    $id = mysqli_escape_string($c, htmlspecialchars($_GET['id']));

    $changeToNonActive = update($c, ['status' => 0], 'semester', "1");
    $update = update($c, ['status' => 1], 'semester', "id_semester=$id");

    if ($update) {
      $_SESSION['alert'] = alert('Data berhasil diupdate', 'success');
      header("Location: ../main.php?p=semester");
    }
  }
}
