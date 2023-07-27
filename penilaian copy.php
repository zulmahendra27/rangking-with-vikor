<div class="page-header">
  <div class="row align-items-end">
    <div class="col-lg-12 d-flex align-items-center justify-content-between">
      <div class="page-header-title d-flex align-items-center">
        <i class="ik ik-edit-1 bg-blue"></i>
        <div class="d-inline">
          <h5 class="mb-0">Data Penilaian Siswa</h5>
        </div>
      </div>
      <?php if ($_SESSION['level'] == 'guru') : ?>
      <a href="?p=penilaian_add" class="btn btn-success"><i class="ik ik-plus-circle"></i>Tambah Data</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body pb-0">
        <div class="row">
          <?php if ($_SESSION['level'] != 'wali') :  ?>
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
          <?php
          endif;
          if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'guru') : ?>
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
          <?php
          endif;
          if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'wali') :
          ?>
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
            <select name="siswa" id="siswa" class="form-control" onchange="changeSiswa(event)">
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
    </div>
    <div class="card-body">
      <table id="tabelData" class="table table-striped table-bordered dt-responsive nowrap">
        <thead>
          <tr>
            <th>No</th>
            <?php if ($_SESSION['level'] == 'guru') : ?>
            <th>Siswa</th>
            <?php else : ?>
            <th>Mata Pelajaran</th>
            <th>Guru Pengampu</th>
            <?php endif; ?>
            <th>Nilai</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          if ($_SESSION['level'] == 'guru' || $_SESSION['level'] == 'admin') :
            if ($_GET['m']) :
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
              endforeach;
            endif;
          endif;
          if ($_SESSION['level'] == 'wali' || $_SESSION['level'] == 'admin') :
            if ($_GET['s']) :
              $join = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa INNER JOIN pengampu c ON b.id_rombel=c.id_rombel INNER JOIN guru d ON c.id_guru=d.id_guru INNER JOIN mapel e ON c.id_mapel=e.id_mapel";
              $where = "a.id_siswa=$siswa";
              $opt = array(
                'join' => $join,
                'where' => $where
              );
              $query = select($c, 'penilaian a', $opt);
              while ($data = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $data['nama_mapel'] ?></td>
            <td><?= $data['nama_guru'] ?></td>
            <td><?= $data['nilai'] ?></td>
          </tr>
          <?php $i++;
              endwhile;
            endif;
          endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="penilaianModal" tabindex="-1" role="dialog" aria-labelledby="penilaianModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_penilaian" id="id_penilaian">
        <div class="modal-header">
          <h5 class="modal-title" id="penilaianModalLabel">Data Penilaian Siswa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="mapel" class="col-sm-3 col-form-label">Nama Mapel</label>
            <div class="col-sm-9">
              <select name="id_mapel" id="mapel" class="form-control">
                <?php
                $queryMapel = select($c, 'mapel');
                while ($data = mysqli_fetch_assoc($queryMapel)) :
                ?>
                <option value="<?= $data['id_mapel'] ?>"><?= $data['nama_mapel'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="rombel" class="col-sm-3 col-form-label">Nama Rombel</label>
            <div class="col-sm-9">
              <select name="id_rombel" id="rombel" class="form-control">
                <?php
                $queryMapel = select($c, 'rombel');
                while ($data = mysqli_fetch_assoc($queryMapel)) :
                ?>
                <option value="<?= $data['id_rombel'] ?>"><?= $data['nama_rombel'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="guru" class="col-sm-3 col-form-label">Nama Guru</label>
            <div class="col-sm-9">
              <select name="id_guru" id="guru" class="form-control">
                <?php
                // $queryMapel = select($c, 'guru');
                $queryMapel = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
                while ($data = mysqli_fetch_assoc($queryMapel)) :
                ?>
                <option value="<?= $data['id_guru'] ?>"><?= $data['nama_guru'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="buttonForm" name="penilaian" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function changeRombel(e) {
  window.location.href = `?p=penilaian&r=${e.target.value}`;
}

function changeSiswa(e) {
  let rombel = document.getElementById('rombel');

  window.location.href = `?p=penilaian&r=${rombel.value}&s=${e.target.value}`;
}

function changeMapel(e) {
  let rombel = document.getElementById('rombel');

  window.location.href = `?p=penilaian_add&r=${rombel.value}&m=${e.target.value}`;
}
</script>