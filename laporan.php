<?php if ($_SESSION['level'] == 'guru' || $_SESSION['level'] == 'kepsek') {
  $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
  $dataGuru = mysqli_fetch_assoc($queryGuru);
} elseif ($_SESSION['level'] == 'wali') {
  $queryWali = select($c, 'users a', ['join' => 'INNER JOIN wali b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
  $dataWali = mysqli_fetch_assoc($queryWali);
} elseif ($_SESSION['level'] == 'siswa') {
  $querySiswa = select($c, 'users a', ['join' => 'INNER JOIN siswa b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
  $dataSiswa = mysqli_fetch_assoc($querySiswa);
}

$querySemesterActive = select($c, 'semester', ['where' => "status=1"]);
$semesterActive = mysqli_fetch_assoc($querySemesterActive);
$id_semester_active = $semesterActive['id_semester'];
?>

<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
  </div>
  <?php if (in_array($_SESSION['level'], ['admin', 'guru', 'kepsek'])) : ?>
    <div class="card-body">
      <div class="d-flex" style="gap: 20px; flex-wrap: wrap">
        <?php if (in_array($_SESSION['level'], ['admin', 'kepsek'])) : ?>
          <a href="laporan/laporan.php?p=guru" target="_blank" class="btn btn-primary">Laporan Data Guru</a>
        <?php endif;
        if (in_array($_SESSION['level'], [])) : ?>
          <a href="laporan/laporan.php?p=siswa" target="_blank" class="btn btn-primary">Laporan Data Siswa</a>
        <?php endif;
        if (in_array($_SESSION['level'], ['admin'])) : ?>
          <a href="laporan/laporan.php?p=wali" target="_blank" class="btn btn-primary">Laporan Data Wali</a>
        <?php endif;
        if (in_array($_SESSION['level'], ['admin'])) : ?>
          <a href="laporan/laporan.php?p=rombel" target="_blank" class="btn btn-primary">Laporan Data Rombel</a>
        <?php endif;
        if (in_array($_SESSION['level'], ['admin'])) : ?>
          <a href="laporan/laporan.php?p=mapel" target="_blank" class="btn btn-primary">Laporan Data Mapel</a>
        <?php endif;
        if (in_array($_SESSION['level'], ['admin', 'guru'])) : ?>
          <a href="laporan/laporan.php?p=pengampu" target="_blank" class="btn btn-primary">Laporan Data Pengampu
            Mapel</a>
        <?php endif;
        if (in_array($_SESSION['level'], [])) : ?>
          <a href="laporan/laporan.php?p=penilaian_guru" target="_blank" class="btn btn-primary">Laporan Penilaian Guru</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (in_array($_SESSION['level'], ['admin', 'kepsek'])) : ?>
    <div class="card-body py-0">
      <h6 class="font-weight-bold">Laporan Penilaian Guru Per Semester</h6>
      <form action="laporan/laporan.php?p=penilaian_guru" method="post" target="_blank">
        <div class="row">
          <div class="col-lg-4 form-group">
            <?php
            $querySemester = select($c, 'semester');
            ?>
            <label for="semester_penilaian">Pilih Semester</label>
            <select name="semester_penilaian" id="semester_penilaian" class="form-control">
              <?php if ($querySemester->num_rows > 0) : foreach ($querySemester as $data) : ?>
                  <option value="<?= $data['id_semester'] ?>" <?= $id_semester_active == $data['id_semester'] ? 'selected' : '' ?>>
                    <?= "Semester " . $data['nama_semester'] . " - TP. " . $data['tahun'] . "/" . ($data['tahun'] + 1) ?>
                  </option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <button type="submit" name="laporan_penilaian_guru_persemester" class="btn btn-primary btn-block">Laporan
              Penilaian Guru</button>
          </div>
        </div>
      </form>
    </div>

    <div class="card-body pb-0">
      <h6 class="font-weight-bold">Laporan Data Siswa Per Rombel dan Semester</h6>
      <form action="laporan/laporan.php?p=siswa" method="post" target="_blank">
        <div class="row">
          <div class="col-lg-4 form-group">
            <?php
            $queryRombel = select($c, 'rombel');
            ?>
            <label for="rombel_semester">Pilih Rombel</label>
            <select name="rombel_semester" id="rombel_semester" class="form-control">
              <?php if ($queryRombel->num_rows > 0) : foreach ($queryRombel as $data) : ?>
                  <option value="<?= $data['id_rombel'] ?>"><?= $data['nama_rombel'] ?></option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif ?>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <?php
            $querySemester = select($c, 'semester');
            ?>
            <label for="semester">Pilih Semester</label>
            <select name="semester" id="semester" class="form-control">
              <?php if ($querySemester->num_rows > 0) : foreach ($querySemester as $data) : ?>
                  <option value="<?= $data['id_semester'] ?>" <?= $id_semester_active == $data['id_semester'] ? 'selected' : '' ?>>
                    <?= "Semester " . $data['nama_semester'] . " - TP. " . $data['tahun'] . "/" . ($data['tahun'] + 1) ?>
                  </option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <button type="submit" name="laporan_siswa_perrombel" class="btn btn-primary btn-block">Laporan Siswa Per
              Rombel</button>
          </div>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <?php if (in_array($_SESSION['level'], ['wali'])) : ?>
    <div class="card-body">
      <h6 class="font-weight-bold">Laporan Penilaian Per Siswa</h6>
      <form action="laporan/laporan.php?p=penilaian" method="post" target="_blank">
        <?php if ($_SESSION['level'] != 'siswa') : ?>
          <div class="row">
            <?php if ($_SESSION['level'] != 'wali') : ?>
              <div class="col-lg-4 form-group">
                <?php
                $queryRombel = select($c, 'rombel');
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
                <select name="rombel" id="rombel" class="form-control" onchange="changeRombel<?= $_SESSION['level'] == 'admin' ? 'Admin' : 'Mapel' ?>(event)">
                  <option value="-1">-- Pilih data --</option>
                  <?php if ($queryRombel->num_rows > 0) : foreach ($queryRombel as $data) : ?>
                      <option value="<?= $data['id_rombel'] ?>"><?= $data['nama_rombel'] ?></option>
                    <?php endforeach;
                  else : ?>
                    <option value="-1">-- Tidak ada data --</option>
                  <?php endif ?>
                </select>
              </div>

              <div class="col-lg-4 form-group">
                <label for="mapel">Pilih Mapel</label>
                <select name="id_mapel" id="mapel" class="form-control">
                  <option value="-1">-- Tidak ada data --</option>
                </select>
              </div>

            <?php endif;
            if ($_SESSION['level'] != 'guru') : ?>

              <div class="col-lg-4 form-group">
                <label for="siswa">Pilih Siswa</label>
                <select name="id_siswa" id="siswa" class="form-control">
                  <?php
                  if ($_SESSION['level'] == 'wali') :
                    $queryWali = select($c, 'users a', ['join' => 'INNER JOIN wali b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
                    $dataWali = mysqli_fetch_assoc($queryWali);

                    $join = "INNER JOIN wali b ON a.id_wali=b.id_wali";
                    $where = "b.id_wali=" . $dataWali['id_wali'];
                    $querySiswa = select($c, 'siswa a', ['join' => $join, 'where' => $where]);

                    if ($querySiswa->num_rows > 0) :
                      foreach ($querySiswa as $dataSiswa) :
                  ?>
                        <option value="<?= $dataSiswa['id_siswa'] ?>"><?= $dataSiswa['nama_siswa'] ?></option>
                    <?php endforeach;
                    endif;
                  else : ?>
                    <option value="-1">-- Tidak ada data --</option>
                  <?php endif; ?>
                </select>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <div class="row justify-content-<?= $_SESSION['level'] == 'admin' ? 'end' : 'start' ?>">
          <?php if (in_array($_SESSION['level'], ['guru', 'admin'])) : ?>
            <div class="col-lg-4">
              <button type="submit" name="laporan_permapel" class="btn btn-primary btn-block">Laporan Penilaian Per
                Mapel</button>
            </div>
          <?php endif;
          if (in_array($_SESSION['level'], ['siswa', 'wali', 'admin'])) : ?>
            <div class="col-lg-4">
              <button type="submit" name="laporan_persiswa" class="btn btn-primary btn-block">Laporan Penilaian Per
                Siswa</button>
            </div>
          <?php endif; ?>
        </div>
      </form>
      <?php if (in_array($_SESSION['level'], ['wali', 'siswa'])) : ?>
        <h6 class="font-weight-bold mt-4">Laporan Absensi Per Bulan</h6>
        <form action="laporan/laporan.php?p=absensi" method="post" target="_blank">
          <div class="row">
            <?php if (in_array($_SESSION['level'], ['wali'])) : ?>
              <div class="col-lg-4 form-group">
                <label for="siswa2">Pilih Siswa</label>
                <select name="id_siswa2" id="siswa2" class="form-control">
                  <?php
                  $where = "id_wali=" . $dataWali['id_wali'];
                  $queryDataSiswa = select($c, 'siswa', ['where' => $where]);

                  foreach ($queryDataSiswa as $newDataSiswa) :
                  ?>
                    <option value="<?= $newDataSiswa['id_siswa'] ?>"><?= $newDataSiswa['nama_siswa'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <div class="col-lg-4 form-group">
              <label for="bulan">Bulan</label>
              <select name="bulan" id="bulan" class="form-control">
                <option value="01" <?= date('m') == '01' ? 'selected' : '' ?>>Januari</option>
                <option value="02" <?= date('m') == '02' ? 'selected' : '' ?>>Februari</option>
                <option value="03" <?= date('m') == '03' ? 'selected' : '' ?>>Maret</option>
                <option value="04" <?= date('m') == '04' ? 'selected' : '' ?>>April</option>
                <option value="05" <?= date('m') == '05' ? 'selected' : '' ?>>Mei</option>
                <option value="06" <?= date('m') == '06' ? 'selected' : '' ?>>Juni</option>
                <option value="07" <?= date('m') == '07' ? 'selected' : '' ?>>Juli</option>
                <option value="08" <?= date('m') == '08' ? 'selected' : '' ?>>Agustus</option>
                <option value="09" <?= date('m') == '09' ? 'selected' : '' ?>>September</option>
                <option value="10" <?= date('m') == '10' ? 'selected' : '' ?>>Oktober</option>
                <option value="11" <?= date('m') == '11' ? 'selected' : '' ?>>November</option>
                <option value="12" <?= date('m') == '12' ? 'selected' : '' ?>>Desember</option>
              </select>
            </div>
            <div class="col-lg-4 form-group">
              <label for="tahun">Tahun</label>
              <select name="tahun" id="tahun" class="form-control">
                <?php
                for ($i = (intval(date('Y')) - 2); $i < (intval(date('Y')) + 3); $i++) :
                ?>
                  <option <?= date('Y') == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </div>
          </div>
          <div class="col-lg-4 pl-0">
            <button type="submit" name="absensi_perbulan" class="btn btn-primary btn-block">Laporan Absensi Per
              Bulan</button>
          </div>
        </form>
      <?php else : ?>
        <h6 class="font-weight-bold mt-4">Laporan Absensi Per Tanggal</h6>
        <form action="laporan/laporan.php?p=absensi" method="post" target="_blank">
          <div class="row">
            <div class="col-lg-4 form-group">
              <?php
              if ($_SESSION['level'] == 'admin') {
                $queryRombel = select($c, 'rombel');
              } elseif ($_SESSION['level'] == 'guru') {
                $join = "INNER JOIN pengampu b ON a.id_rombel=b.id_rombel";
                $where = "b.id_guru=" . $dataGuru['id_guru'];
                $queryRombel = select($c, 'rombel a', ['where' => $where, 'join' => $join, 'select' => 'DISTINCT(b.id_rombel), a.*']);
              }
              ?>
              <label for="rombel2">Pilih Rombel</label>
              <select name="id_rombel2" id="rombel2" class="form-control" onchange="changeRombelAbsensi(event)">
                <option value="-1">-- Pilih data --</option>
                <?php if ($queryRombel->num_rows > 0) : foreach ($queryRombel as $data) : ?>
                    <option value="<?= $data['id_rombel'] ?>"><?= $data['nama_rombel'] ?></option>
                  <?php endforeach;
                else : ?>
                  <option value="-1">-- Tidak ada data --</option>
                <?php endif ?>
              </select>
            </div>
            <div class="col-lg-4 form-group">
              <label for="mapel2">Pilih Mapel</label>
              <select name="id_mapel2" id="mapel2" class="form-control">
                <option value="-1">-- Tidak ada data --</option>
              </select>
            </div>
            <div class="col-lg-4 form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-lg-4">
              <button type="submit" name="absensi_pertanggal" class="btn btn-primary btn-block">Laporan Absensi Per
                Tanggal</button>
            </div>
          </div>
        </form>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <script>
    function changeRombelMapel(e) {
      let mapel = document.getElementById('mapel');

      fetch('./control/aksi_select.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            'laporan': 'penilaian_mapel',
            'id_rombel': e.target.value
          })
        })
        .then(response => response.json())
        .then(data => {
          data = data.data_mapel;
          if (data.length <= 0) {
            let option = document.createElement('option');
            mapel.innerText = '';
            option.innerText = '-- Tidak ada data --';
            mapel.append(option);
          } else {
            mapel.innerText = '';
            data.forEach(function(e) {
              let option = document.createElement('option');
              option.setAttribute('value', e.id_mapel);
              option.innerText = e.nama_mapel;
              mapel.append(option);
            });

          }
        })
        .catch(err => console.log(err));
    }

    function changeRombelAdmin(e) {
      let mapel = document.getElementById('mapel');
      let siswa = document.getElementById('siswa');

      fetch('./control/aksi_select.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            'laporan': 'penilaian_mapel',
            'id_rombel': e.target.value
          })
        })
        .then(response => response.json())
        .then(data => {
          let dataMapel = data.data_mapel;
          let dataSiswa = data.data_siswa;
          if (dataMapel.length <= 0) {
            let option = document.createElement('option');
            mapel.innerText = '';
            option.innerText = '-- Tidak ada data --';
            mapel.append(option);
          } else {
            mapel.innerText = '';
            dataMapel.forEach(function(e) {
              let option = document.createElement('option');
              option.setAttribute('value', e.id_mapel);
              option.innerText = e.nama_mapel;
              mapel.append(option);
            });
          }

          if (dataSiswa.length <= 0) {
            let option = document.createElement('option');
            siswa.innerText = '';
            option.innerText = '-- Tidak ada data --';
            siswa.append(option);
          } else {
            siswa.innerText = '';
            dataSiswa.forEach(function(e) {
              let option = document.createElement('option');
              option.setAttribute('value', e.id_siswa);
              option.innerText = e.nama_siswa;
              siswa.append(option);
            });
          }
        })
        .catch(err => console.log(err));
    }

    function changeRombelAbsensi(e) {
      let mapel = document.getElementById('mapel2');

      fetch('./control/aksi_select.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            'absensi': 'absensi',
            'id_rombel': e.target.value
          })
        })
        .then(response => response.json())
        .then(data => {
          data = data.data_mapel;
          if (data.length <= 0) {
            let option = document.createElement('option');
            mapel.innerText = '';
            option.innerText = '-- Tidak ada data --';
            mapel.append(option);
          } else {
            mapel.innerText = '';
            data.forEach(function(e) {
              let option = document.createElement('option');
              option.setAttribute('value', e.id_mapel);
              option.innerText = e.nama_mapel;
              mapel.append(option);
            });

          }
        })
        .catch(err => console.log(err));
    }
  </script>