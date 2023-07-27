<?php if ($_SESSION['level'] == 'guru') {
  $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
  $dataGuru = mysqli_fetch_assoc($queryGuru);
} elseif ($_SESSION['level'] == 'wali') {
  $queryWali = select($c, 'users a', ['join' => 'INNER JOIN wali b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
  $dataWali = mysqli_fetch_assoc($queryWali);
} elseif ($_SESSION['level'] == 'siswa') {
  $querySiswa = select($c, 'users a', ['join' => 'INNER JOIN siswa b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
  $dataSiswa = mysqli_fetch_assoc($querySiswa);
} ?>

<form action="./control/aksi_insert.php" method="post">
  <div class="card overflow-hidden" style="border-radius: 7px;">
    <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
      <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    </div>
    <div class="card-body pb-0">

      <div class="row">
        <?php if (in_array($_SESSION['level'], ['admin', 'guru'])) : ?>
          <div class="col-lg-4 form-group">
            <?php
            if ($_SESSION['level'] == 'admin') {
              $queryRombel = select($c, 'rombel');
            } elseif ($_SESSION['level'] == 'guru') {
              $join = "INNER JOIN pengampu b ON a.id_rombel=b.id_rombel";
              $where = "b.id_guru=" . $dataGuru['id_guru'];
              $queryRombel = select($c, 'rombel a', ['where' => $where, 'join' => $join, 'select' => 'DISTINCT(b.id_rombel), a.*']);
            }
            if (!isset($_GET['r'])) {
              if ($queryRombel->num_rows > 0) {
                $rombel = mysqli_fetch_assoc($queryRombel)['id_rombel'];
              } else {
                $rombel = -1;
              }
            } else {
              $rombel = mysqli_escape_string($c, htmlspecialchars($_GET['r']));
            }
            ?>
            <label for="rombel">Pilih Rombel</label>
            <select name="id_rombel" id="rombel" class="form-control" onchange="changeRombel(event)">
              <?php if ($queryRombel->num_rows > 0) : foreach ($queryRombel as $data) : ?>
                  <option <?= $data['id_rombel'] == $rombel ? 'selected' : '' ?> value="<?= $data['id_rombel'] ?>">
                    <?= $data['nama_rombel'] ?></option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif ?>
            </select>
          </div>

          <div class="col-lg-4 form-group">
            <?php
            if ($_SESSION['level'] == 'admin') {
              $queryMapel = select($c, 'mapel a');
            } else {
              $join = "INNER JOIN pengampu b ON a.id_mapel=b.id_mapel";
              $where = "b.id_rombel=$rombel AND b.id_guru=" . $dataGuru['id_guru'];
              $queryMapel = select($c, 'mapel a', ['where' => $where, 'join' => $join]);
            }

            if (!isset($_GET['m'])) {
              if ($queryMapel->num_rows > 0) {
                $mapel = mysqli_fetch_assoc($queryMapel)['id_mapel'];
              } else {
                $mapel = -1;
              }
            } else {
              $mapel = mysqli_escape_string($c, htmlspecialchars($_GET['m']));
            }
            ?>
            <label for="mapel">Pilih Mapel</label>
            <select name="id_mapel" id="mapel" class="form-control" onchange="changeMapel(event)">
              <?php if ($queryMapel->num_rows > 0) : foreach ($queryMapel as $data) : ?>
                  <option <?= $data['id_mapel'] == $mapel ? 'selected' : '' ?> value="<?= $data['id_mapel'] ?>">
                    <?= $data['nama_mapel'] ?></option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif; ?>
            </select>
          </div>

          <div class="col-lg-4 form-group">
            <?php
            if (!isset($_GET['t'])) {
              $tanggal = date('Y-m-d');
            } else {
              $tanggal = mysqli_escape_string($c, htmlspecialchars($_GET['t']));
            }
            ?>
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= isset($_GET['t']) ? $_GET['t'] : date('Y-m-d') ?>" onchange="changeTanggal(event)">
          </div>
        <?php endif;
        if (in_array($_SESSION['level'], ['wali', 'siswa'])) : ?>

          <?php if ($_SESSION['level'] == 'wali') : ?>
            <div class="col-lg-4 form-group">
              <label for="siswa">Siswa</label>
              <select name="id_siswa" id="siswa" class="form-control" onchange="changeSiswa(event)">
                <?php
                $where = "id_wali=" . $dataWali['id_wali'];
                $queryDataSiswa = select($c, 'siswa', ['where' => $where]);

                if (!isset($_GET['s'])) {
                  if ($queryDataSiswa->num_rows > 0) {
                    $siswa = mysqli_fetch_assoc($queryDataSiswa)['id_siswa'];
                  } else {
                    $siswa = -1;
                  }
                } else {
                  $siswa = mysqli_escape_string($c, htmlspecialchars($_GET['s']));
                }

                foreach ($queryDataSiswa as $newDataSiswa) :
                ?>
                  <option <?= $newDataSiswa['id_siswa'] == $siswa ? 'selected' : '' ?> value="<?= $newDataSiswa['id_siswa'] ?>">
                    <?= $newDataSiswa['nama_siswa'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
          <div class="col-lg-4 form-group">
            <label for="bulan">Bulan</label>
            <select name="bulan" id="bulan" class="form-control" onchange="changeBulan<?= $_SESSION['level'] == 'siswa' ? 'Siswa' : '' ?>(event)">
              <?php
              if (!isset($_GET['b'])) {
                $bulan = date('m');
              } else {
                $bulan = mysqli_escape_string($c, htmlspecialchars($_GET['b']));
              }
              ?>
              <option value="01" <?= $bulan == '01' ? 'selected' : '' ?>>Januari</option>
              <option value="02" <?= $bulan == '02' ? 'selected' : '' ?>>Februari</option>
              <option value="03" <?= $bulan == '03' ? 'selected' : '' ?>>Maret</option>
              <option value="04" <?= $bulan == '04' ? 'selected' : '' ?>>April</option>
              <option value="05" <?= $bulan == '05' ? 'selected' : '' ?>>Mei</option>
              <option value="06" <?= $bulan == '06' ? 'selected' : '' ?>>Juni</option>
              <option value="07" <?= $bulan == '07' ? 'selected' : '' ?>>Juli</option>
              <option value="08" <?= $bulan == '08' ? 'selected' : '' ?>>Agustus</option>
              <option value="09" <?= $bulan == '09' ? 'selected' : '' ?>>September</option>
              <option value="10" <?= $bulan == '10' ? 'selected' : '' ?>>Oktober</option>
              <option value="11" <?= $bulan == '11' ? 'selected' : '' ?>>November</option>
              <option value="12" <?= $bulan == '12' ? 'selected' : '' ?>>Desember</option>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="tahun">Tahun</label>
            <select name="tahun" id="tahun" class="form-control" onchange="changeTahun<?= $_SESSION['level'] == 'siswa' ? 'Siswa' : '' ?>(event)">
              <?php
              if (!isset($_GET['th'])) {
                $tahun = date('Y');
              } else {
                $tahun = mysqli_escape_string($c, htmlspecialchars($_GET['th']));
              }
              for ($i = (intval(date('Y')) - 2); $i < (intval(date('Y')) + 3); $i++) :
              ?>
                <option <?= $tahun == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php if (in_array($_SESSION['level'], ['admin', 'guru'])) : ?>
      <div class="card-body table-responsive">
        <table class="small table table-striped table-bordered dt-responsive nowrap">
          <thead>
            <tr>
              <th>No</th>
              <th>Siswa</th>
              <?php if ($_SESSION['level'] == 'guru') : ?>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alfa</th>
              <?php else : ?>
                <th>Status</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            $joinAbsensi = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa";
            $whereAbsensi = "a.id_mapel=$mapel AND b.id_rombel=$rombel AND a.tanggal='$tanggal'";
            $queryAbsensi = select($c, 'absensi a', ['join' => $joinAbsensi, 'where' => $whereAbsensi]);
            $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$rombel"]);

            $arrayAbsensi = array();
            foreach ($queryAbsensi as $dataAbsensi) {
              $arrayAbsensi[$dataAbsensi['id_siswa']] = $dataAbsensi['status'];
            }

            foreach ($querySiswa as $dataSiswa) :
              $absensi = array_key_exists($dataSiswa['id_siswa'], $arrayAbsensi) ? $arrayAbsensi[$dataSiswa['id_siswa']] : '';
            ?>
              <tr>
                <th><?= $i ?></th>
                <th><?= $dataSiswa['nama_siswa'] ?></th>
                <?php if ($_SESSION['level'] == 'guru') : ?>
                  <th><input <?= ($absensi == 'hadir' || $absensi == '') ? 'checked' : '' ?> type="radio" name="status-<?= $dataSiswa['id_siswa'] ?>" value="hadir"></th>
                  <th><input <?= $absensi == 'sakit' ? 'checked' : '' ?> type="radio" name="status-<?= $dataSiswa['id_siswa'] ?>" value="sakit"></th>
                  <th><input <?= $absensi == 'izin' ? 'checked' : '' ?> type="radio" name="status-<?= $dataSiswa['id_siswa'] ?>" value="izin"></th>
                  <th><input <?= $absensi == 'alfa' ? 'checked' : '' ?> type="radio" name="status-<?= $dataSiswa['id_siswa'] ?>" value="alfa"></th>
                <?php else : ?>
                  <th><?= ucwords($absensi) ?></th>
                <?php endif; ?>
              </tr>
            <?php
              $i++;
            endforeach; ?>
          </tbody>
        </table>
        <?php if ($_SESSION['level'] == 'guru') : ?>
          <?php /* if ($queryAbsensi->num_rows > 0) : */ ?>
          <button type="submit" name="absensi" class="btn btn-sm btn-primary">Simpan Data Absensi</button>
          <?php /* endif; */ ?>
        <?php endif; ?>
      </div>
    <?php endif;
    if (in_array($_SESSION['level'], ['wali', 'siswa'])) : ?>
      <div class="card-body table-responsive">
        <table class="small table table-striped table-bordered dt-responsive nowrap">
          <thead>
            <tr>
              <th>No</th>
              <th>Siswa</th>
              <th>Mata Pelajaran</th>
              <th>Tanggal</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;

            if ($_SESSION['level'] == 'siswa') {
              $siswa = $dataSiswa['id_siswa'];
            }

            $day = $tahun . "-" . $bulan . "-01";
            $lastDay = date('Y-m-d', strtotime("last day of this month", strtotime($day)));

            $joinAbsensi = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa INNER JOIN mapel c ON a.id_mapel=c.id_mapel";
            $whereAbsensi = "a.id_siswa=$siswa AND a.tanggal BETWEEN '$day' AND '$lastDay'";
            $queryAbsensi = select($c, 'absensi a', ['join' => $joinAbsensi, 'where' => $whereAbsensi]);

            $arrayAbsensi = array();

            foreach ($queryAbsensi as $dataSiswa) :
            ?>
              <tr>
                <th><?= $i ?></th>
                <th><?= $dataSiswa['nama_siswa'] ?></th>
                <th><?= $dataSiswa['nama_mapel'] ?></th>
                <th><?= date('d-m-Y', strtotime($dataSiswa['tanggal'])) ?></th>
                <th><?= ucwords($dataSiswa['status']) ?></th>
              </tr>
            <?php
              $i++;
            endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</form>

<script>
  function changeRombel(e) {
    window.location.href = `?p=absensi&r=${e.target.value}`;
  }

  function changeMapel(e) {
    let rombel = document.getElementById('rombel');

    window.location.href = `?p=absensi&r=${rombel.value}&m=${e.target.value}`;
  }

  function changeTanggal(e) {
    let rombel = document.getElementById('rombel');
    let mapel = document.getElementById('mapel');

    window.location.href = `?p=absensi&r=${rombel.value}&m=${mapel.value}&t=${e.target.value}`;
  }

  function changeSiswa(e) {
    window.location.href = `?p=absensi&s=${e.target.value}`;
  }

  function changeBulan(e) {
    let siswa = document.getElementById('siswa');

    window.location.href = `?p=absensi&s=${siswa.value}&b=${e.target.value}`;
  }

  function changeBulanSiswa(e) {
    window.location.href = `?p=absensi&b=${e.target.value}`;
  }

  function changeTahunSiswa(e) {
    let bulan = document.getElementById('bulan');

    window.location.href = `?p=absensi&b=${bulan.value}&th=${e.target.value}`;
  }


  function changeSiswaWali(e) {
    let rombel = document.getElementById('rombel');

    window.location.href = `?p=absensi&s=${e.target.value}`;
  }

  function changeMapel(e) {
    let rombel = document.getElementById('rombel');

    window.location.href = `?p=absensi&r=${rombel.value}&m=${e.target.value}`;
  }

  function changeSiswaAdmin(e) {
    let rombel = document.getElementById('rombel');
    let mapel = document.getElementById('mapel');

    window.location.href = `?p=absensi&r=${rombel.value}&m=${mapel.value}&s=${e.target.value}`;
  }

  function changeMapelAdmin(e) {
    let rombel = document.getElementById('rombel');
    let siswa = document.getElementById('siswa');

    window.location.href = `?p=absensi&r=${rombel.value}&m=${e.target.value}&s=${siswa.value}`;
  }
</script>