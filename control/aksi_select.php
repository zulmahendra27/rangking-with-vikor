<?php
include_once 'connection.php';
include_once 'helper.php';

if (isset($_POST['detail'])) {
  if ($_POST['detail'] == 'guru') {
    $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
    $where = array(
      'where' => "guru.id_user=$id_user",
      'join' => "INNER JOIN users ON users.id_user=guru.id_user",
      'select' => "users.username, users.level, guru.*"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'guru', $where)));
  } elseif ($_POST['detail'] == 'siswa') {
    $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
    $join = "INNER JOIN users ON users.id_user=siswa.id_user INNER JOIN rombel ON siswa.id_rombel=rombel.id_rombel INNER JOIN wali ON siswa.id_wali=wali.id_wali";
    $where = array(
      'where' => "siswa.id_user=$id_user",
      'join' => $join,
      'select' => "users.username, users.level, siswa.*, rombel.*, wali.id_wali, wali.nama_wali"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'siswa', $where)));
  } elseif ($_POST['detail'] == 'wali') {
    $id_user = mysqli_escape_string($c, htmlspecialchars($_POST['id_user']));
    $where = array(
      'where' => "wali.id_user=$id_user",
      'join' => "INNER JOIN users ON users.id_user=wali.id_user",
      'select' => "users.username, users.level, wali.*"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'wali', $where)));
  } elseif ($_POST['detail'] == 'rombel') {
    $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel']));
    $where = array(
      'where' => "rombel.id_rombel=$id_rombel",
      'join' => "INNER JOIN guru ON rombel.walas=guru.id_guru"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'rombel', $where)));
  } elseif ($_POST['detail'] == 'mapel') {
    $id_mapel = mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel']));
    $where = array(
      'where' => "id_mapel=$id_mapel"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'mapel', $where)));
  } elseif ($_POST['detail'] == 'pengampu') {
    $id_pengampu = mysqli_escape_string($c, htmlspecialchars($_POST['id_pengampu']));
    $where = array(
      'where' => "id_pengampu=$id_pengampu"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'pengampu', $where)));
  } elseif ($_POST['detail'] == 'kriteria') {
    $id_kriteria = mysqli_escape_string($c, htmlspecialchars($_POST['id_kriteria']));
    $where = array(
      'where' => "id_kriteria=$id_kriteria"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'kriteria', $where)));
  } elseif ($_POST['detail'] == 'subkriteria') {
    $id_subkriteria = mysqli_escape_string($c, htmlspecialchars($_POST['id_subkriteria']));
    $where = array(
      'where' => "id_subkriteria=$id_subkriteria"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'subkriteria', $where)));
  } elseif ($_POST['detail'] == 'semester') {
    $id_semester = mysqli_escape_string($c, htmlspecialchars($_POST['id_semester']));
    $where = array(
      'where' => "id_semester=$id_semester"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'semester', $where)));
  }
} elseif (isset($_POST['laporan'])) {
  session_start();
  if ($_POST['laporan'] == 'penilaian_mapel') {
    $newData = array();
    $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel']));

    if ($_SESSION['level'] == 'guru') {
      $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
      $dataGuru = mysqli_fetch_assoc($queryGuru);
      $where = "b.id_rombel=$id_rombel AND b.id_guru=" . $dataGuru['id_guru'];
    } else {
      $where = "b.id_rombel=$id_rombel";

      // $id_siswa = mysqli_escape_string($c, htmlspecialchars($_POST['id_siswa']));

      $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$id_rombel"]);

      $arraySiswa = array();

      foreach ($querySiswa as $dataSiswa) {
        array_push($arraySiswa, $dataSiswa);
      }

      $newData['data_siswa'] = $arraySiswa;
    }

    $join = "INNER JOIN pengampu b ON a.id_mapel=b.id_mapel";
    $queryMapel = select($c, 'mapel a', ['where' => $where, 'join' => $join]);

    $array = array();

    foreach ($queryMapel as $data) {
      array_push($array, $data);
    }

    $newData['data_mapel'] = $array;

    echo json_encode($newData);
  } elseif ($_POST['laporan'] == 'penilaian_siswa') {
    $id_siswa = mysqli_escape_string($c, htmlspecialchars($_POST['id_siswa']));
    $opt = array(
      'where' => "a.id_siswa=$id_siswa"
    );

    echo json_encode(mysqli_fetch_assoc(select($c, 'penilaian a', $opt)));
  }
} elseif (isset($_POST['absensi'])) {
  session_start();
  if ($_POST['absensi'] == 'absensi') {
    $newData = array();
    $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel']));

    if ($_SESSION['level'] == 'guru') {
      $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
      $dataGuru = mysqli_fetch_assoc($queryGuru);
      $where = "b.id_rombel=$id_rombel AND b.id_guru=" . $dataGuru['id_guru'];
    } else {
      $where = "b.id_rombel=$id_rombel";
    }

    $join = "INNER JOIN pengampu b ON a.id_mapel=b.id_mapel";
    $queryMapel = select($c, 'mapel a', ['where' => $where, 'join' => $join]);

    $array = array();

    foreach ($queryMapel as $data) {
      array_push($array, $data);
    }

    $newData['data_mapel'] = $array;

    echo json_encode($newData);
  }
}
