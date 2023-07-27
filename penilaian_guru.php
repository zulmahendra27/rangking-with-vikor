<?php
if (!in_array($_SESSION['level'], ['admin', 'guru', 'kepsek'])) {
  echo "<script>alert('Anda tidak diizinkan mengakses halaman ini');</script>";
  echo "<script>window.location='?p=dashboard';</script>";
}
?>

<style>
div.h1>svg {
  height: 200px;
  width: 200px;
}
</style>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
  </div>
  <?php
  if ($_SESSION['level'] == 'admin') :
    $queryLog = select($c, 'log', ['limit' => 1, 'order' => 'time DESC']);
    $log = mysqli_fetch_assoc($queryLog)['time'];
  ?>
  <div class="card-body">

    <?php
      $querySemesterActive = select($c, 'semester', ['where' => "status=1"]);
      $semesterActive = mysqli_fetch_assoc($querySemesterActive);
      $id_semester_active = $semesterActive['id_semester'];

      $selectC3C5 = "id_guru, SUM(C31) as c31, SUM(C32) as c32, SUM(C33) as c33, SUM(C34) as c34, SUM(C35) as c35, SUM(C36) as c36, SUM(C37) as c37, SUM(C51) as c51, SUM(C52) as c52, SUM(C53) as c53, SUM(C54) as c54, SUM(C55) as c55, SUM(C56) as c56, SUM(C57) as c57, COUNT(C31) as jc";
      $queryC3C5 = select($c, 'nilaidarisiswa', ['select' => $selectC3C5, 'group' => 'id_guru', 'where' => "id_semester=$id_semester_active"]);

      if ($queryC3C5->num_rows > 0) :
      ?>
    <h4 class="text-dark font-weight-500 mb-4">Data berikut merupakan Update Penilaian Tanggal
      <span class="text-danger"><?= date('d-m-Y', strtotime($log)) ?></span>, Jam <span
        class="text-danger"><?= date('H:i', strtotime($log)) ?></span>.<br>Untuk
      mengupdate penilaian,
      silahkan lakukan penilaian ulang kemudian tekan tombol "Simpan Penilaian".
    </h4>
    <h4 class="text-dark">Silahkan lakukan penilaian terhadap guru sesuai kriteria yang ada.</h4>

    <form action="./control/aksi_insert.php" method="post" class="table-responsive">
      <?php
          $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
          $queryKriteria = select($c, 'kriteria');
          $whereNilai = "id_semester=$id_semester_active";
          $queryNilaiGuru = select($c, 'nilaiguru', ['where' => $whereNilai]);
          $querySiswa = select($c, 'siswa', ['select' => 'COUNT(id_siswa) as jumlahsiswa']);
          // $querySiswa = $c->query("SELECT COUNT(id_siswa) as jumlahsiswa FROM siswa");

          $selectAbsensi = "id_guru, COUNT(waktu) as absensi";
          $queryAbsensi = select($c, 'absensiguru', ['select' => $selectAbsensi, 'group' => 'id_guru']);

          $jumlahSiswa = mysqli_fetch_assoc($querySiswa)['jumlahsiswa'];
          $maxNilaiGuru = 5 * intval($jumlahSiswa);

          $dataNilai = array();
          if ($queryNilaiGuru->num_rows > 0) {
            foreach ($queryNilaiGuru as $row) {
              $dataNilai[] = $row['id_guru'] . '-' . $row['id_kriteria'] . '-' . $row['nilai_guru'];
            }
          }
          ?>

      <div class="form-group mb-4">
        <button type="submit" name="penilaian_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>

      <table width="100%" class="small table table-striped table-bordered nowrap">
        <thead class="text-center align-items-center">
          <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Guru</th>
            <th colspan="<?= $queryKriteria->num_rows ?>">Kriteria Penilaian</th>
          </tr>
          <tr>
            <?php
                if ($queryKriteria->num_rows > 0) :
                  foreach ($queryKriteria as $kriteria) :
                ?>
            <th class="text-wrap"><?= $kriteria['kode_kriteria'] ?></th>
            <?php endforeach;
                endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
              if ($queryGuru->num_rows > 0) :
                foreach ($queryGuru as $key => $data) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $data['nama_guru'] ?></td>
            <?php
                    if ($queryKriteria->num_rows > 0) :
                      foreach ($queryKriteria as $key => $kriteria) :
                    ?>

            <td>
              <select name="kriteria-<?= $key ?>[]" class="form-control form-control-sm"
                <?= !in_array($kriteria['kode_kriteria'], ['C1', 'C4']) ? 'readonly' : '' ?>>
                <?php
                            if ($kriteria['kode_kriteria'] == 'C1') {
                              $id_kriteria = $kriteria['id_kriteria'];
                              $whereSub = "id_kriteria=$id_kriteria";
                              $querySub = select($c, 'subkriteria', ['where' => $whereSub]);
                              if ($querySub->num_rows > 0) :
                                foreach ($querySub as $sub) :
                                  $check = $data['id_guru'] . '-' . $kriteria['id_kriteria'] . '-' . $sub['bobot_sub'];
                                  $search = array_search($check, $dataNilai); ?>
                <option value="<?= $sub['bobot_sub'] ?>" <?= $search !== false ? 'selected' : '' ?>>
                  <?= $sub['nama_sub'] ?>
                </option>
                <?php endforeach;
                              endif;
                            } elseif ($kriteria['kode_kriteria'] == 'C2') {
                              if ($data['pendidikan'] == 'S2') {
                                $nilaiPendidikan = 1;
                              } elseif ($data['pendidikan'] == 'S1') {
                                $nilaiPendidikan = 0.75;
                              } elseif ($data['pendidikan'] == 'DIV') {
                                $nilaiPendidikan = 0.5;
                              } elseif ($data['pendidikan'] == 'DIII') {
                                $nilaiPendidikan = 0.2;
                              } elseif ($data['pendidikan'] == 'DII') {
                                $nilaiPendidikan = 0.05;
                              }
                              $rentangPendidikan = $data['pendidikan'];

                              ?>
                <option value="<?= $nilaiPendidikan ?? 0; ?>">
                  <?= $rentangPendidikan ?? '0 %'; ?>
                </option>
                <?php

                            } elseif ($kriteria['kode_kriteria'] == 'C3' || $kriteria['kode_kriteria'] == 'C5') {
                              foreach ($queryC3C5 as $c3c5) {
                                $nC3C5[] = $c3c5['id_guru'];
                                if ($c3c5['id_guru'] == $data['id_guru']) {

                                  if ($kriteria['kode_kriteria'] == 'C3') {

                                    $nilaiSumC3 = number_format(((($c3c5['c31'] + $c3c5['c32'] + $c3c5['c33'] + $c3c5['c34'] + $c3c5['c35'] + $c3c5['c36'] + $c3c5['c37']) / ($c3c5['jc'] * 7)) * 10), 0, ',', '.');

                                    if ($nilaiSumC3 >= 81) {
                                      $nilaiC3 = 1;
                                      // $rentang = "81-100";
                                    } elseif ($nilaiSumC3 >= 61) {
                                      $nilaiC3 = 0.75;
                                      // $rentang = "61-80";
                                    } elseif ($nilaiSumC3 >= 41) {
                                      $nilaiC3 = 0.5;
                                      // $rentang = "41-60";
                                    } elseif ($nilaiSumC3 >= 21) {
                                      $nilaiC3 = 0.25;
                                      // $rentang = "21-40";
                                    } else {
                                      $nilaiC3 = 0;
                                      // $rentang = "0-20";
                                    }
                                    $rentangC3 = $nilaiSumC3 . " %";
                                    // $rentang = $nilaiC3;
                                  } elseif ($kriteria['kode_kriteria'] == 'C5') {

                                    $nilaiSumC5 = number_format(((($c3c5['c51'] + $c3c5['c52'] + $c3c5['c53'] + $c3c5['c54'] + $c3c5['c55'] + $c3c5['c56'] + $c3c5['c57']) / ($c3c5['jc'] * 7)) * 10), 0, ',', '.');

                                    if ($nilaiSumC5 >= 81) {
                                      $nilaiC5 = 1;
                                      // $rentang = "81-100";
                                    } elseif ($nilaiSumC5 >= 61) {
                                      $nilaiC5 = 0.75;
                                      // $rentang = "61-80";
                                    } elseif ($nilaiSumC5 >= 41) {
                                      $nilaiC5 = 0.5;
                                      // $rentang = "41-60";
                                    } elseif ($nilaiSumC5 >= 21) {
                                      $nilaiC5 = 0.25;
                                      // $rentang = "21-40";
                                    } else {
                                      $nilaiC5 = 0;
                                      // $rentang = "0-20";
                                    }
                                    $rentangC5 = $nilaiSumC5 . " %";
                                    // $rentang = $nilaiC5;
                                  }
                                }
                              }
                              if ($kriteria['kode_kriteria'] == 'C3') {
                              ?>
                <option value="<?= in_array($data['id_guru'], $nC3C5) ? $nilaiC3 : 0; ?>">
                  <?= in_array($data['id_guru'], $nC3C5) ? $rentangC3 : '0 %'; ?>
                </option>
                <?php
                              } elseif ($kriteria['kode_kriteria'] == 'C5') {
                              ?>
                <option value="<?= in_array($data['id_guru'], $nC3C5) ? $nilaiC5 : 0; ?>">
                  <?= in_array($data['id_guru'], $nC3C5) ? $rentangC5 : '0 %'; ?>
                </option>
                <?php
                              }
                            } else {
                              $id_kriteria = $kriteria['id_kriteria'];
                              $whereSub = "id_kriteria=$id_kriteria";
                              $querySub = select($c, 'subkriteria', ['where' => $whereSub]);
                              if ($querySub->num_rows > 0) :
                                foreach ($querySub as $sub) :
                                  $check = $data['id_guru'] . '-' . $kriteria['id_kriteria'] . '-' . $sub['bobot_sub'];
                                  $search = array_search($check, $dataNilai); ?>
                <option value="<?= $sub['bobot_sub'] ?>" <?= $search !== false ? 'selected' : '' ?>>
                  <?= $sub['nama_sub'] ?>
                </option>
                <?php endforeach;
                              endif;
                            }  ?>

              </select>
            </td>
            <?php
                      endforeach;
                    endif; ?>
          </tr>
          <?php $i++;
                endforeach;
              endif; ?>
        </tbody>
      </table>
      <div class="form-group">
        <button type="submit" name="penilaian_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>
    </form>
    <?php else : ?>
    <h3 class="text-dark font-weight-bold mb-4">Penilaian guru belum dapat dilakukan karena belum ada satupun siswa yang
      melakukan penilaian terhadap guru pada <span class="text-danger">Semester
        <?= $semesterActive['nama_semester'] . " - TP. " . $semesterActive['tahun'] . "/" . ($semesterActive['tahun'] + 1); ?></span>.
    </h3>
    <?php endif; ?>

  </div>
  <?php elseif (in_array($_SESSION['level'], ['guru', 'kepsek'])) : ?>

  <div class="card-body">
    <div class="row">
      <div class="col-lg-4 form-group">
        <?php
          $querySemester = select($c, 'semester');
          if (!isset($_GET['s'])) {
            if ($querySemester->num_rows > 0) {
              $semester = mysqli_fetch_assoc($querySemester)['id_semester'];
            } else {
              $semester = -1;
            }
          } else {
            $semester = mysqli_escape_string($c, htmlspecialchars($_GET['s']));
          }
          ?>
        <label for="semester">Pilih Semester</label>
        <select name="semester" id="semester" class="form-control" onchange="changeSemester(event)">
          <?php if ($querySemester->num_rows > 0) : foreach ($querySemester as $data) : ?>
          <option <?= $data['id_semester'] == $semester ? 'selected' : '' ?> value="<?= $data['id_semester'] ?>">
            Semester <?= $data['nama_semester'] . " - TP. " . $data['tahun'] . "/" . ($data['tahun'] + 1); ?>
          </option>
          <?php endforeach;
            else : ?>
          <option value="-1">-- Tidak ada data --</option>
          <?php endif ?>
        </select>
      </div>
    </div>
    <div class="table-responsive">

      <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
        <thead class="text-center align-items-center">
          <tr>
            <th>Rangking</th>
            <th>Nama Guru</th>
            <th>Nilai Q</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $join = 'INNER JOIN guru b ON a.id_guru=b.id_guru';
            $whereSemester = "id_semester=$semester";
            $query = select($c, 'hasilnilai a', ['join' => $join, 'where' => $whereSemester]);
            if ($query->num_rows > 0) :
              $i = 1;
              foreach ($query as $data) :
                $color = ($i == 1 ? 'bg-secondary text-white' : ($i == 2 ? 'bg-info text-white' : ($i == 3 ? 'bg-warning text-white' : '')));
                $tag = in_array($i, [1, 2, 3]) ? 'th' : 'td';
            ?>
          <tr class="<?= $color ?>">
            <<?= $tag ?>><?= $i ?></<?= $tag ?>>
            <<?= $tag ?>><?= $data['nama_guru'] ?></<?= $tag ?>>
            <<?= $tag ?>><?= $data['nilai'] ?></<?= $tag ?>>
          </tr>
          <?php
                $i++;
              endforeach;
            else :
              ?>
          <tr>
            <th colspan="3" class="text-center font-weight-bold">-- Belum ada data penilaian --</th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>

    </div>
  </div>

  <script>
  function changeSemester(e) {
    window.location.href = `?p=penilaian_guru&s=${e.target.value}`;
  }
  </script>
  <?php endif; ?>
</div>