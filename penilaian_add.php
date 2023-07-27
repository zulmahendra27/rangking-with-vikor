<?php
if ($_SESSION['level'] != 'guru') {
  echo "<script>alert('Anda tidak diizinkan mengakses fitur ini!');</script>";
  echo "<script>window.location='?p=dashboard';</script>";
}
?>

<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <a href="?p=penilaian" class="btn btn-sm btn-info"><i class="ik ik-plus-circle"></i>Kembali</a>
  </div>
  <form action="./control/aksi_insert.php" method="post">
    <div class="card-body pb-0">
      <div class="row">
        <div class="col-lg-4 form-group">
          <?php
          if ($_SESSION['level'] == 'guru') {
            $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
            $dataGuru = mysqli_fetch_assoc($queryGuru);
          }

          $join = "INNER JOIN pengampu b ON a.id_rombel=b.id_rombel";
          $where = "b.id_guru=" . $dataGuru['id_guru'];
          $queryRombel = select($c, 'rombel a', ['join' => $join, 'where' => $where, 'select' => "DISTINCT(b.id_rombel), a.*"]);
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
          $join = "INNER JOIN pengampu b ON a.id_mapel=b.id_mapel";
          $where = "b.id_rombel=$rombel AND b.id_guru=" . $dataGuru['id_guru'];
          $queryMapel = select($c, 'mapel a', ['where' => $where, 'join' => $join]);
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
      </div>
      <?php
      $joinNilai = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa";
      $whereNilai = "a.id_mapel=$mapel AND b.id_rombel=$rombel";
      $queryNilai = select($c, 'penilaian a', ['join' => $joinNilai, 'where' => $whereNilai]);
      $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$rombel"]);

      if ($querySiswa->num_rows > 0) :
      ?>
      <button type="submit" name="penilaian" class="btn btn-primary">Simpan Penilaian</button>
      <?php endif; ?>
    </div>
    <div class="card-body table-responsive">
      <table class="small table table-striped table-bordered dt-responsive nowrap">
        <thead>
          <tr>
            <th>No.</th>
            <th>Siswa</th>
            <th class="nosort sorting_disabled">Nilai</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $arrayNilai = array();
          foreach ($queryNilai as $dataNilai) {
            $arrayNilai[$dataNilai['id_siswa']] = $dataNilai['nilai'];
          }

          $i = 1;
          foreach ($querySiswa as $dataSiswa) :
            $nilai = array_key_exists($dataSiswa['id_siswa'], $arrayNilai) ? $arrayNilai[$dataSiswa['id_siswa']] : 0;
          ?>
          <tr>
            <th><?= $i ?></th>
            <th><?= $dataSiswa['nama_siswa'] ?></th>
            <th class="p-2">
              <input type="number" name="nilai-<?= $dataSiswa['id_siswa'] ?>" class="form-control form-control-sm"
                min="0" value="<?= $nilai ?>">
            </th>
          </tr>
          <?php
            $i++;
          endforeach; ?>
        </tbody>
      </table>

    </div>
  </form>
</div>

<script>
function changeRombel(e) {
  window.location.href = `?p=penilaian_add&r=${e.target.value}`;
}

function changeMapel(e) {
  let rombel = document.getElementById('rombel');

  window.location.href = `?p=penilaian_add&r=${rombel.value}&m=${e.target.value}`;
}
</script>