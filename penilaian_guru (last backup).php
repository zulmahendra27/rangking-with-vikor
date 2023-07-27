<?php
if (!in_array($_SESSION['level'], ['admin', 'guru'])) {
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
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
  </div>
  <?php
  if ($_SESSION['level'] == 'admin') :
    $queryLog = select($c, 'log', ['limit' => 1, 'order' => 'time DESC']);
    $log = mysqli_fetch_assoc($queryLog)['time'];
  ?>
  <div class="card-body">

    <h4 class="text-dark font-weight-500 mb-4">Data berikut merupakan Update Penilaian Tanggal
      <span class="text-danger"><?= date('d-m-Y', strtotime($log)) ?></span>, Jam <span
        class="text-danger"><?= date('H:i', strtotime($log)) ?></span>.<br>Untuk
      mengupdate penilaian,
      silahkan lakukan penilaian ulang kemudian tekan tombol "Simpan Penilaian".
    </h4>
    <h4 class="text-dark">Silahkan lakukan penilaian terhadap guru sesuai kriteria yang ada.</h4>

    <form action="./control/aksi_insert.php" method="post" class="table-responsive">
      <?php
        // $queryGuru = select($c, 'guru');
        $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
        $queryKriteria = select($c, 'kriteria');
        $queryNilaiGuru = select($c, 'nilaiguru');
        $querySiswa = select($c, 'siswa', ['select' => 'COUNT(id_siswa) as jumlahsiswa']);
        // $querySiswa = $c->query("SELECT COUNT(id_siswa) as jumlahsiswa FROM siswa");

        $selectAbsensi = "id_guru, COUNT(waktu) as absensi";
        $selectC3C5 = "id_guru, SUM(C31) as c31, SUM(C32) as c32, SUM(C51) as c51, SUM(C52) as c52";
        $queryAbsensi = select($c, 'absensiguru', ['select' => $selectAbsensi, 'group' => 'id_guru']);
        $queryC3C5 = select($c, 'nilaidarisiswa', ['select' => $selectC3C5, 'group' => 'id_guru']);

        $jumlahSiswa = mysqli_fetch_assoc($querySiswa)['jumlahsiswa'];
        $maxNilaiGuru = 5 * intval($jumlahSiswa);

        $dataNilai = array();
        if ($queryNilaiGuru->num_rows > 0) {
          while ($row = mysqli_fetch_assoc($queryNilaiGuru)) {
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
                <?= $kriteria['kode_kriteria'] != 'C4' ? 'readonly' : '' ?>>
                <?php
                          if ($kriteria['kode_kriteria'] == 'C1') {

                            foreach ($queryAbsensi as $absensi) {
                              if ($absensi['id_guru'] == $data['id_guru']) {
                                if (intval($absensi['absensi']) >= 91) {
                                  $nilaiAbsensi = 1;
                                  // $rentang = "91-156";
                                } elseif (intval($absensi['absensi']) >= 71) {
                                  $nilaiAbsensi = 0.75;
                                  // $rentang = "71-90";
                                } elseif (intval($absensi['absensi']) >= 51) {
                                  $nilaiAbsensi = 0.5;
                                  // $rentang = "51-70";
                                } elseif (intval($absensi['absensi']) >= 31) {
                                  $nilaiAbsensi = 0.25;
                                  // $rentang = "31-50";
                                } else {
                                  $nilaiAbsensi = 0;
                                  // $rentang = "0-30";
                                }
                                $rentangAbsensi = $absensi['absensi'] . " kali";
                              }
                            }
                          ?>
                <option value="<?= $nilaiAbsensi ?? 0; ?>">
                  <?= $rentangAbsensi ?? '0 %'; ?>
                </option>
                <?php
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

                                  $nilaiSumC3 = $c3c5['c31'] + $c3c5['c32'];

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

                                  $nilaiSumC5 = $c3c5['c51'] + $c3c5['c52'];

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


  </div>
  <?php elseif ($_SESSION['level'] == 'guru') : ?>

  <div class="card-body">
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

            $query = select($c, 'hasilnilai a', ['join' => $join]);
            if ($query->num_rows > 0) :
              $i = 1;
              foreach ($query as $data) :
                $color = ($i == 1 ? 'bg-success text-white' : ($i == 2 ? 'bg-info text-white' : ($i == 3 ? 'bg-warning text-white' : '')));
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
            endif;
            ?>
        </tbody>
      </table>

    </div>
  </div>
  <?php endif; ?>
</div>