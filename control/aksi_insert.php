<?php
include_once 'connection.php';
include_once 'helper.php';
session_start();
date_default_timezone_set("Asia/Jakarta");

if (isset($_POST['guru'])) {
  $status = mysqli_escape_string($c, htmlspecialchars($_POST['status']));
  $username = mysqli_escape_string($c, htmlspecialchars($_POST['username']));

  if ($status == 'asn') {
    $nip = $username;
  } else {
    $nip = '';
  }

  $data_user = array(
    'username' => $username,
    'password' => password_hash($_POST['username'], PASSWORD_DEFAULT),
    'level' => 'guru'
  );

  $query_user = insert($c, $data_user, 'users');

  $data_guru = array(
    'id_user' => mysqli_insert_id($c),
    'nip' => $nip,
    'nama_guru' => mysqli_escape_string($c, htmlspecialchars($_POST['nama'])),
    'pendidikan' => mysqli_escape_string($c, htmlspecialchars($_POST['pendidikan'])),
    'email' => mysqli_escape_string($c, htmlspecialchars($_POST['email'])),
    'nohp' => mysqli_escape_string($c, htmlspecialchars($_POST['nohp']))
  );

  $query_guru = insert($c, $data_guru, 'guru');

  if ($query_user && $query_guru) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=guru");
  }
} elseif (isset($_POST['siswa'])) {
  $nisn = mysqli_escape_string($c, htmlspecialchars($_POST['nisn']));

  $data_user = array(
    'username' => $nisn,
    'password' => password_hash($_POST['nisn'], PASSWORD_DEFAULT),
    'level' => 'siswa'
  );

  $query_user = insert($c, $data_user, 'users');

  $data_siswa = array(
    'id_user' => mysqli_insert_id($c),
    'id_rombel' => mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel'])),
    'id_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['id_wali'])),
    'nama_siswa' => mysqli_escape_string($c, htmlspecialchars($_POST['nama'])),
    'nisn' => $nisn,
    'email_siswa' => mysqli_escape_string($c, htmlspecialchars($_POST['email'])),
    'nohp_siswa' => mysqli_escape_string($c, htmlspecialchars($_POST['nohp']))
  );

  $query_siswa = insert($c, $data_siswa, 'siswa');

  if ($query_user && $query_siswa) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=siswa");
  }
} elseif (isset($_POST['wali'])) {
  $data_user = array(
    'username' => mysqli_escape_string($c, htmlspecialchars($_POST['username'])),
    'password' => password_hash($_POST['username'], PASSWORD_DEFAULT),
    'level' => 'wali'
  );

  $query_user = insert($c, $data_user, 'users');

  $data_wali = array(
    'id_user' => mysqli_insert_id($c),
    'nama_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['nama'])),
    'email_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['email'])),
    'nohp_wali' => mysqli_escape_string($c, htmlspecialchars($_POST['nohp']))
  );

  $query_wali = insert($c, $data_wali, 'wali');

  if ($query_user && $query_wali) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=wali");
  }
} elseif (isset($_POST['rombel'])) {
  $data = array(
    'walas' => mysqli_escape_string($c, htmlspecialchars($_POST['walas'])),
    'nama_rombel' => mysqli_escape_string($c, htmlspecialchars($_POST['nama']))
  );

  $query = insert($c, $data, 'rombel');

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=rombel");
  }
} elseif (isset($_POST['mapel'])) {
  $data = array(
    'nama_mapel' => mysqli_escape_string($c, htmlspecialchars($_POST['nama']))
  );

  $query = insert($c, $data, 'mapel');

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=mapel");
  }
} elseif (isset($_POST['pengampu'])) {
  $data = array(
    'id_mapel' => mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel'])),
    'id_rombel' => mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel'])),
    'id_guru' => mysqli_escape_string($c, htmlspecialchars($_POST['id_guru']))
  );

  $query = insert($c, $data, 'pengampu');

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=pengampu");
  }
} elseif (isset($_POST['penilaian'])) {
  $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel']));
  $id_mapel = mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel']));
  $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$id_rombel"]);

  $joinNilai = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa";
  $whereNilai = "a.id_mapel=$id_mapel AND b.id_rombel=$id_rombel";
  $queryNilai = select($c, 'penilaian a', ['join' => $joinNilai, 'where' => $whereNilai]);

  if ($queryNilai->num_rows <= 0) {
    $arraySiswa = array();
    $arrayNilai = array();

    foreach ($querySiswa as $dataSiswa) {
      $nilai = mysqli_escape_string($c, htmlspecialchars($_POST['nilai-' . $dataSiswa['id_siswa']]));
      array_push($arraySiswa, $dataSiswa['id_siswa']);
      array_push($arrayNilai, $nilai);
    }

    $data = array(
      'id_siswa' => $arraySiswa,
      'id_mapel' => $id_mapel,
      'nilai' => $arrayNilai
    );

    $query = insert_nilai($c, $data, 'penilaian');
  } else {
    foreach ($querySiswa as $dataSiswa) {
      $nilai = mysqli_escape_string($c, htmlspecialchars($_POST['nilai-' . $dataSiswa['id_siswa']]));
      $where = "id_siswa=" . $dataSiswa['id_siswa'] . " AND id_mapel=$id_mapel";
      $querySiswaPenilaian = select($c, 'penilaian', ['where' => $where]);

      if ($querySiswaPenilaian->num_rows > 0) {
        $nilaiDb = mysqli_fetch_assoc($querySiswaPenilaian);
        if ($nilaiDb['nilai'] != $nilai) {
          $query = update($c, ['nilai' => $nilai], 'penilaian', $where);
        }
        continue;
      }

      $dataInput = array(
        'id_siswa' => $dataSiswa['id_siswa'],
        'id_mapel' => $id_mapel,
        'nilai' => $nilai
      );
      $query = insert($c, $dataInput, 'penilaian');
    }


    // if (count($arraySiswa) > $queryNilai->num_rows) {
    //   foreach ($arraySiswa as $key => $siswaInput) {
    //     foreach ($queryNilai as $nilaiDb) {
    //       if ($siswaInput == $nilaiDb['id_siswa']) {
    //         continue;
    //       } else {
    //         $where = "id_siswa=" . $siswaInput . " AND id_mapel=$id_mapel";
    //         $querySiswaPenilaian = select($c, 'penilaian', ['where' => $where]);
    //         if ($querySiswaPenilaian->num_rows > 0) {
    //           $query = update($c, ['nilai' => $nilaiInput], 'penilaian', "id_siswa=$nilaiInput");
    //         }
    //         continue;
    //       }

    //       $dataInput = array(
    //         'id_siswa' => $arraySiswa[$key],
    //         'id_mapel' => $id_mapel,
    //         'nilai' => $nilaiInput
    //       );
    //       $query = insert($c, $dataInput, 'penilaian');
    //     }
    //   }
    // } else {
    //   foreach ($arrayNilai as $key => $nilaiInput) {
    //     foreach ($queryNilai as $nilaiDb) {
    //       if ($nilaiInput == $nilaiDb['nilai']) {
    //         continue;
    //       }

    //       $dataInput = array(
    //         'id_siswa' => $arraySiswa[$key],
    //         'id_mapel' => $id_mapel,
    //         'nilai' => $nilaiInput
    //       );
    //       $query = insert($c, $dataInput, 'penilaian');
    //     }
    //   }
    // }
  }

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=penilaian_add");
  }
} elseif (isset($_POST['absensi'])) {
  $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['id_rombel']));
  $id_mapel = mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel']));
  $tanggal = mysqli_escape_string($c, htmlspecialchars($_POST['tanggal']));
  $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$id_rombel"]);

  $joinAbsensi = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa";
  $whereAbsensi = "a.id_mapel=$id_mapel AND b.id_rombel=$id_rombel AND a.tanggal='$tanggal'";
  $queryAbsensi = select($c, 'absensi a', ['join' => $joinAbsensi, 'where' => $whereAbsensi]);

  if ($queryAbsensi->num_rows <= 0) {
    $arraySiswa = array();
    $arrayAbsensi = array();

    foreach ($querySiswa as $dataSiswa) {
      $absensi = mysqli_escape_string($c, htmlspecialchars($_POST['status-' . $dataSiswa['id_siswa']]));
      array_push($arraySiswa, $dataSiswa['id_siswa']);
      array_push($arrayAbsensi, $absensi);
    }

    $data = array(
      'id_siswa' => $arraySiswa,
      'id_mapel' => $id_mapel,
      'tanggal' => $tanggal,
      'status' => $arrayAbsensi
    );

    $query = insert_absensi($c, $data, 'absensi');
  } else {
    foreach ($querySiswa as $dataSiswa) {
      $absensi = mysqli_escape_string($c, htmlspecialchars($_POST['status-' . $dataSiswa['id_siswa']]));
      $where = "id_siswa=" . $dataSiswa['id_siswa'] . " AND id_mapel=$id_mapel AND tanggal='$tanggal'";
      $querySiswaAbsensi = select($c, 'absensi', ['where' => $where]);

      if ($querySiswaAbsensi->num_rows > 0) {
        $absensiDb = mysqli_fetch_assoc($querySiswaAbsensi);
        if ($absensiDb['status'] != $absensi) {
          $query = update($c, ['status' => "$absensi"], 'absensi', $where);
        }
        continue;
      }

      $dataInput = array(
        'id_siswa' => $dataSiswa['id_siswa'],
        'id_mapel' => $id_mapel,
        'tanggal' => "$tanggal",
        'status' => "$absensi"
      );
      $query = insert($c, $dataInput, 'absensi');
    }


    // if (count($arraySiswa) > $queryAbsensi->num_rows) {
    //   foreach ($arraySiswa as $key => $siswaInput) {
    //     foreach ($queryAbsensi as $absensiDb) {
    //       if ($siswaInput == $absensiDb['id_siswa']) {
    //         continue;
    //       } else {
    //         $where = "id_siswa=" . $siswaInput . " AND id_mapel=$id_mapel";
    //         $querySiswaAbsensi = select($c, 'absensi', ['where' => $where]);
    //         if ($querySiswaAbsensi->num_rows > 0) {
    //           $query = update($c, ['absensi' => $absensiInput], 'absensi', "id_siswa=$absensiInput");
    //         }
    //         continue;
    //       }

    //       $dataInput = array(
    //         'id_siswa' => $arraySiswa[$key],
    //         'id_mapel' => $id_mapel,
    //         'absensi' => $absensiInput
    //       );
    //       $query = insert($c, $dataInput, 'absensi');
    //     }
    //   }
    // } else {
    //   foreach ($arrayAbsensi as $key => $absensiInput) {
    //     foreach ($queryAbsensi as $absensiDb) {
    //       if ($absensiInput == $absensiDb['absensi']) {
    //         continue;
    //       }

    //       $dataInput = array(
    //         'id_siswa' => $arraySiswa[$key],
    //         'id_mapel' => $id_mapel,
    //         'absensi' => $absensiInput
    //       );
    //       $query = insert($c, $dataInput, 'absensi');
    //     }
    //   }
    // }
  }

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=absensi");
  }
} elseif (isset($_POST['kriteria'])) {
  $data = array(
    'kode_kriteria' => mysqli_escape_string($c, htmlspecialchars($_POST['kode_kriteria'])),
    'nama_kriteria' => mysqli_escape_string($c, htmlspecialchars($_POST['nama_kriteria'])),
    'bobot' => mysqli_escape_string($c, htmlspecialchars($_POST['bobot']))
  );

  $query = insert($c, $data, 'kriteria');

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=kriteria");
  }
} elseif (isset($_POST['penilaian_guru'])) {
  // var_dump($_POST['kriteria-2']);
  // die;
  $querySemesterActive = select($c, 'semester', ['where' => "status=1"]);
  $semesterActive = mysqli_fetch_assoc($querySemesterActive);
  $id_semester_active = $semesterActive['id_semester'];

  delete($c, 'nilaiguru', "id_semester=$id_semester_active");

  $data['key'] = ['id_guru', 'id_kriteria', 'id_semester', 'nilai_guru'];
  $data['values'] = [];

  $queryKriteria = select($c, 'kriteria');

  $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
  foreach ($queryGuru as $keyGuru => $guru) {
    $id_guru = $guru['id_guru'];
    foreach ($queryKriteria as $keyKriteria => $kriteria) {
      $id_kriteria = $kriteria['id_kriteria'];
      $nilai = $_POST['kriteria-' . $keyKriteria][$keyGuru];

      // Jika Input Manual Menggunakan Tag INPUT
      // $kode_kriteria = $kriteria['kode_kriteria'];
      // if ($kode_kriteria == 'C1') {
      //   if ($nilai > 90) {
      //     $nilai = 1;
      //   } elseif ($nilai > 70) {
      //     $nilai = 0.75;
      //   } elseif ($nilai > 50) {
      //     $nilai = 0.5;
      //   } elseif ($nilai > 30) {
      //     $nilai = 0.25;
      //   } else {
      //     $nilai = 0;
      //   }
      // }

      $data['values'][] = "(" . $guru['id_guru'] . "," . $kriteria['id_kriteria'] . "," . $id_semester_active . "," . $nilai . ")";
    }
  }

  $query = insert_batch($c, $data, 'nilaiguru');

  if ($query) {
    insert($c, [], 'log');
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=penilaian_guru");
  }
} elseif (isset($_POST['penilaian_guru_backup'])) {
  $username = $_SESSION['username'];
  $joinSiswa = "INNER JOIN users b ON a.id_user=b.id_user";
  $querySiswa = select($c, 'siswa a', ['join' => $joinSiswa, 'where' => "b.username=$username", 'select' => "a.*"]);
  $id_siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];

  $queryNilai = select($c, 'nilaiguru', ['select' => "COUNT(id_nilaiguru) AS jumlahbysiswa", 'where' => "id_siswa=$id_siswa"]);
  $jumlahBySiswa = mysqli_fetch_assoc($queryNilai)['jumlahbysiswa'];

  if ($jumlahBySiswa <= 0) {
    $data['key'] = ['id_guru', 'id_siswa', 'id_kriteria', 'nilai_guru'];
    $data['values'] = [];

    $queryKriteria = select($c, 'kriteria');

    $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
    foreach ($queryGuru as $keyGuru => $guru) {
      $id_guru = $guru['id_guru'];
      foreach ($queryKriteria as $keyKriteria => $kriteria) {
        $id_kriteria = $kriteria['id_kriteria'];
        $nilai = $_POST['kriteria-' . $keyKriteria][$keyGuru];
        $data['values'][] = "(" . $guru['id_guru'] . "," . $id_siswa . "," . $kriteria['id_kriteria'] . "," . $nilai . ")";
      }
    }

    $query = insert_batch($c, $data, 'nilaiguru');
  } else {
  }

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=penilaian_guru");
  }
} elseif (isset($_POST['subkriteria'])) {
  $id_kriteria = mysqli_escape_string($c, htmlspecialchars($_POST['id_kriteria']));

  $data = array(
    'id_kriteria' => $id_kriteria,
    'nama_sub' => mysqli_escape_string($c, htmlspecialchars($_POST['nama_sub'])),
    'bobot_sub' => mysqli_escape_string($c, htmlspecialchars($_POST['bobot_sub']))
  );

  $query = insert($c, $data, 'subkriteria');

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=sub_kriteria&k=$id_kriteria");
  }
} elseif (isset($_POST['absensi_guru'])) {
  $id_guru = mysqli_escape_string($c, htmlspecialchars($_POST['id_guru']));

  $select = "COUNT(*) as jumlah";
  $checkAbsensi = select($c, 'absensiguru', ['select' => $select, 'where' => "id_guru=$id_guru"]);

  if (mysqli_fetch_assoc($checkAbsensi)['jumlah'] <= 0) {
    $waktu = date('Y-m-d H:i:s');

    $data = array(
      'id_guru' => $id_guru,
      'waktu' => $waktu
    );

    $query = insert($c, $data, 'absensiguru');

    if ($query) {
      $_SESSION['alert'] = alert('Absensi berhasil diambil', 'success');
      header("Location: ../main.php?p=absensi_guru");
    }
  } else {
    $_SESSION['alert'] = alert('Anda telah melakukan absensi hari ini', 'danger');
    header("Location: ../main.php?p=absensi_guru");
  }
} elseif (isset($_POST['kinerja_guru_backup'])) {
  if ($_SESSION['level'] == 'siswa') {
    $username = $_SESSION['username'];
    $joinSiswa = "INNER JOIN users b ON a.id_user=b.id_user";
    $querySiswa = select($c, 'siswa a', ['join' => $joinSiswa, 'where' => "b.username=$username", 'select' => "a.*"]);
    $id_siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];

    $queryNilai = select($c, 'nilaidarisiswa', ['select' => "COUNT(*) AS jumlahbysiswa", 'where' => "id_siswa=$id_siswa"]);
    $jumlahBySiswa = mysqli_fetch_assoc($queryNilai)['jumlahbysiswa'];

    $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
    if ($jumlahBySiswa <= 0) {
      $data['key'] = ['id_siswa', 'id_guru', 'C31', 'C32', 'C51', 'C52'];
      $data['values'] = [];

      foreach ($queryGuru as $keyGuru => $guru) {
        $id_guru = $guru['id_guru'];

        $C31 = $_POST['C31'][$keyGuru];
        $C32 = $_POST['C32'][$keyGuru];
        $C51 = $_POST['C51'][$keyGuru];
        $C52 = $_POST['C52'][$keyGuru];
        $data['values'][] = "(" . $id_siswa . "," . $guru['id_guru'] . "," . $C31 . "," . $C32 . "," . $C51 . "," . $C52 . ")";
      }

      $query = insert_batch($c, $data, 'nilaidarisiswa');
    } else {
      foreach ($queryGuru as $keyGuru => $guru) {
        $id_guru = $guru['id_guru'];

        $C31 = $_POST['C31'][$keyGuru];
        $C32 = $_POST['C32'][$keyGuru];
        $C51 = $_POST['C51'][$keyGuru];
        $C52 = $_POST['C52'][$keyGuru];

        $whereNilai = "id_siswa=" . $id_siswa . " AND id_guru=" . $guru['id_guru'];
        $queryNilai = select($c, 'nilaidarisiswa', ['where' => $whereNilai]);

        if ($queryNilai->num_rows > 0) {
          $nilaiDb = mysqli_fetch_assoc($queryNilai);
          if ($nilaiDb['C31'] != $C31 || $nilaiDb['C32'] != $C32 || $nilaiDb['C51'] != $C51 || $nilaiDb['C52'] != $C52) {
            $dataUpdate = array(
              'C31' => $C31,
              'C32' => $C32,
              'C51' => $C51,
              'C52' => $C52
            );
            $query = update($c, $dataUpdate, 'nilaidarisiswa', $whereNilai);
          }
          continue;
        }

        $dataInput = array(
          'id_siswa' => $id_siswa,
          'id_guru' => $guru['id_guru'],
          'C31' => $C31,
          'C32' => $C32,
          'C51' => $C51,
          'C52' => $C52
        );
        $query = insert($c, $dataInput, 'nilaidarisiswa');
      }
    }

    if ($query) {
      $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
      header("Location: ../main.php?p=penilaian_kinerja_guru");
    }
  }
} elseif (isset($_POST['kinerja_guru'])) {
  if ($_SESSION['level'] == 'siswa') {
    $querySemesterActive = select($c, 'semester', ['where' => "status=1"]);
    $semesterActive = mysqli_fetch_assoc($querySemesterActive);
    $id_semester_active = $semesterActive['id_semester'];

    $username = $_SESSION['username'];
    $joinSiswa = "INNER JOIN users b ON a.id_user=b.id_user";
    $querySiswa = select($c, 'siswa a', ['join' => $joinSiswa, 'where' => "b.username=$username", 'select' => "a.*"]);
    $id_siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];
    $id_guru = mysqli_escape_string($c, htmlspecialchars($_POST['id_guru']));

    $whereNilai = "id_siswa=$id_siswa AND id_guru=$id_guru AND id_semester=$id_semester_active";
    $queryNilai = select($c, 'nilaidarisiswa', ['select' => "COUNT(*) AS jumlahbysiswa", 'where' => $whereNilai]);
    $jumlahBySiswa = mysqli_fetch_assoc($queryNilai)['jumlahbysiswa'];

    $C31 = mysqli_escape_string($c, htmlspecialchars($_POST['C31']));
    $C32 = mysqli_escape_string($c, htmlspecialchars($_POST['C32']));
    $C33 = mysqli_escape_string($c, htmlspecialchars($_POST['C33']));
    $C34 = mysqli_escape_string($c, htmlspecialchars($_POST['C34']));
    $C35 = mysqli_escape_string($c, htmlspecialchars($_POST['C35']));
    $C36 = mysqli_escape_string($c, htmlspecialchars($_POST['C36']));
    $C37 = mysqli_escape_string($c, htmlspecialchars($_POST['C37']));
    $C51 = mysqli_escape_string($c, htmlspecialchars($_POST['C51']));
    $C52 = mysqli_escape_string($c, htmlspecialchars($_POST['C52']));
    $C53 = mysqli_escape_string($c, htmlspecialchars($_POST['C53']));
    $C54 = mysqli_escape_string($c, htmlspecialchars($_POST['C54']));
    $C55 = mysqli_escape_string($c, htmlspecialchars($_POST['C55']));
    $C56 = mysqli_escape_string($c, htmlspecialchars($_POST['C56']));
    $C57 = mysqli_escape_string($c, htmlspecialchars($_POST['C57']));

    if ($jumlahBySiswa <= 0) {
      $data = [
        'id_siswa' => $id_siswa,
        'id_guru' => $id_guru,
        'id_semester' => $id_semester_active,
        'C31' => $C31,
        'C32' => $C32,
        'C33' => $C33,
        'C34' => $C34,
        'C35' => $C35,
        'C36' => $C36,
        'C37' => $C37,
        'C51' => $C51,
        'C52' => $C52,
        'C53' => $C53,
        'C54' => $C54,
        'C55' => $C55,
        'C56' => $C56,
        'C57' => $C57
      ];

      $query = insert($c, $data, 'nilaidarisiswa');
    } else {
      $data = [
        'C31' => $C31,
        'C32' => $C32,
        'C33' => $C33,
        'C34' => $C34,
        'C35' => $C35,
        'C36' => $C36,
        'C37' => $C37,
        'C51' => $C51,
        'C52' => $C52,
        'C53' => $C53,
        'C54' => $C54,
        'C55' => $C55,
        'C56' => $C56,
        'C57' => $C57
      ];

      $whereUpdate = "id_siswa=$id_siswa AND id_guru=$id_guru AND id_semester=$id_semester_active";

      $query = update($c, $data, 'nilaidarisiswa', $whereUpdate);
    }

    if ($query) {
      $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
      header("Location: ../main.php?p=penilaian_kinerja_guru&g=$id_guru");
    }
  }
} elseif (isset($_POST['semester'])) {
  $data = array(
    'nama_semester' => mysqli_escape_string($c, htmlspecialchars($_POST['nama_semester'])),
    'tahun' => mysqli_escape_string($c, htmlspecialchars($_POST['tahun'])),
    'status' => 0
  );

  $query = insert($c, $data, 'semester');

  if ($query) {
    $_SESSION['alert'] = alert('Data berhasil disimpan', 'success');
    header("Location: ../main.php?p=semester");
  }
}
