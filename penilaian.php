<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <?php if ($_SESSION['level'] == 'guru') : ?>
      <a href="?p=penilaian_add" class="btn btn-sm btn-info"><i class="ik ik-plus-circle"></i>Tambah Data</a>
    <?php endif; ?>
  </div>
  <?php if ($_SESSION['level'] != 'siswa') : ?>
    <div class="card-body pb-0">

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
            <select name="rombel" id="rombel" class="form-control" onchange="changeRombel(event)">
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
              $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
              $dataGuru = mysqli_fetch_assoc($queryGuru);

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
            <select name="id_mapel" id="mapel" class="form-control" onchange="changeMapel<?= $_SESSION['level'] == 'admin' ? 'Admin' : '' ?>(event)">
              <?php if ($queryMapel->num_rows > 0) : foreach ($queryMapel as $data) : ?>
                  <option <?= $data['id_mapel'] == $mapel ? 'selected' : '' ?> value="<?= $data['id_mapel'] ?>">
                    <?= $data['nama_mapel'] ?></option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif; ?>
            </select>
          </div>

        <?php endif;
        if ($_SESSION['level'] != 'guru') : ?>

          <div class="col-lg-4 form-group">
            <?php
            if ($_SESSION['level'] == 'wali') {
              $queryWali = select($c, 'users a', ['join' => 'INNER JOIN wali b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
              $dataWali = mysqli_fetch_assoc($queryWali);

              $querySiswa = select($c, 'siswa', ['where' => "id_wali=" . $dataWali['id_wali']]);
            } else {
              $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$rombel"]);
            }

            if (!isset($_GET['s'])) {
              if ($querySiswa->num_rows > 0) {
                $siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];
              } else {
                $siswa = -1;
              }
            } else {
              $siswa = mysqli_escape_string($c, htmlspecialchars($_GET['s']));
            }
            ?>
            <label for="siswa">Pilih Siswa</label>
            <select name="id_siswa" id="siswa" class="form-control" onchange="changeSiswa<?= $_SESSION['level'] == 'admin' ? 'Admin' : ($_SESSION['level'] == 'wali' ? 'Wali' : '') ?>(event)">
              <?php if ($querySiswa->num_rows > 0) : foreach ($querySiswa as $data) : ?>
                  <option <?= $data['id_siswa'] == $siswa ? 'selected' : '' ?> value="<?= $data['id_siswa'] ?>">
                    <?= $data['nama_siswa'] ?></option>
                <?php endforeach;
              else : ?>
                <option value="-1">-- Tidak ada data --</option>
              <?php endif; ?>
            </select>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php if ($_SESSION['level'] != 'wali') : ?>
      <div class="card-body table-responsive">
        <h5 class="font-weight-bold mb-3">Filter by Mata Pelajaran</h5>
        <table id="tabelData" class="small table table-striped table-bordered dt-responsive nowrap">
          <thead>
            <tr>
              <th>No</th>
              <th>Siswa</th>
              <th>Nilai</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            $joinNilai = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa";
            $whereNilai = "a.id_mapel=$mapel AND b.id_rombel=$rombel";
            $queryNilai = select($c, 'penilaian a', ['join' => $joinNilai, 'where' => $whereNilai]);
            $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$rombel"]);

            $arrayNilai = array();
            foreach ($queryNilai as $dataNilai) {
              $arrayNilai[$dataNilai['id_siswa']] = $dataNilai['nilai'];
            }

            foreach ($querySiswa as $dataSiswa) :
              $nilai = array_key_exists($dataSiswa['id_siswa'], $arrayNilai) ? $arrayNilai[$dataSiswa['id_siswa']] : 0;
            ?>
              <tr>
                <th><?= $i ?></th>
                <th><?= $dataSiswa['nama_siswa'] ?></th>
                <th><?= $nilai ?></th>
              </tr>
            <?php
              $i++;
            endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif;
  endif;
  if ($_SESSION['level'] != 'guru') : ?>
    <div class="card-body table-responsive">
      <h5 class="font-weight-bold mb-3">Filter by Siswa</h5>
      <table id="tabelData2" class="small table table-striped table-bordered dt-responsive nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Guru Pengampu</th>
            <th>Nilai</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;

          if ($_SESSION['level'] == 'siswa') {
            $querySiswa = select($c, 'users a', ['join' => 'INNER JOIN siswa b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
            $dataSiswa = mysqli_fetch_assoc($querySiswa);

            $where = "a.id_siswa=" . $dataSiswa['id_siswa'];
          } else {
            $where = "a.id_siswa=$siswa";
          }

          $join = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa INNER JOIN mapel c ON a.id_mapel=c.id_mapel INNER JOIN rombel d ON b.id_rombel=d.id_rombel";
          $opt = array(
            'join' => $join,
            'where' => $where
          );
          $query = select($c, 'penilaian a', $opt);
          while ($data = mysqli_fetch_assoc($query)) :
            $join = "INNER JOIN mapel b ON a.id_mapel=b.id_mapel INNER JOIN guru c ON a.id_guru=c.id_guru";
            $where = "a.id_mapel=" . $data['id_mapel'] . " AND a.id_rombel=" . $data['id_rombel'];
            $dataGuru = mysqli_fetch_assoc(select($c, 'pengampu a', ['where' => $where, 'join' => $join]));
          ?>
            <tr>
              <td><?= $i ?></td>
              <td><?= $data['nama_mapel'] ?></td>
              <td><?= $dataGuru['nama_guru'] ?></td>
              <td><?= $data['nilai'] ?></td>
            </tr>
          <?php $i++;
          endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script>
  function changeRombel(e) {
    window.location.href = `?p=penilaian&r=${e.target.value}`;
  }

  function changeSiswa(e) {
    let rombel = document.getElementById('rombel');

    window.location.href = `?p=penilaian&r=${rombel.value}&s=${e.target.value}`;
  }

  function changeSiswaWali(e) {
    let rombel = document.getElementById('rombel');

    window.location.href = `?p=penilaian&s=${e.target.value}`;
  }

  function changeMapel(e) {
    let rombel = document.getElementById('rombel');

    window.location.href = `?p=penilaian&r=${rombel.value}&m=${e.target.value}`;
  }

  function changeSiswaAdmin(e) {
    let rombel = document.getElementById('rombel');
    let mapel = document.getElementById('mapel');

    window.location.href = `?p=penilaian&r=${rombel.value}&m=${mapel.value}&s=${e.target.value}`;
  }

  function changeMapelAdmin(e) {
    let rombel = document.getElementById('rombel');
    let siswa = document.getElementById('siswa');

    window.location.href = `?p=penilaian&r=${rombel.value}&m=${e.target.value}&s=${siswa.value}`;
  }
</script>