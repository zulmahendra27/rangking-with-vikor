<div class="page-header">
  <div class="row align-items-end">
    <div class="col-lg-12 d-flex align-items-center justify-content-between">
      <div class="page-header-title d-flex align-items-center">
        <i class="ik ik-file-text bg-blue"></i>
        <div class="d-inline">
          <h5 class="mb-0">Laporan</h5>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <?php if (in_array($_SESSION['level'], ['admin', 'guru'])) : ?>
      <div class="card-body">
        <div class="d-flex" style="gap: 20px; flex-wrap: wrap">
          <?php if (in_array($_SESSION['level'], ['admin'])) : ?>
          <a href="laporan/laporan.php?p=guru" target="_blank" class="btn btn-primary">Laporan Data Guru</a>
          <?php endif;
            if (in_array($_SESSION['level'], ['admin', 'guru'])) : ?>
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
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
      <div class="card-body">
        <h6 class="font-weight-bold">Laporan Per Siswa</h6>
        <form action="laporan/laporan.php?p=penilaian" method="post" target="_blank">
          <div class="row">
            <?php if (in_array($_SESSION['level'], ['admin', 'wali'])) : ?>
            <div class="col-lg-4 form-group">

              <label for="siswa">Pilih Siswa</label>
              <select name="id_siswa" id="siswa" class="form-control">
                <?php
                  if ($_SESSION['level'] == 'admin') {
                    $querySiswa = select($c, 'siswa');
                  } elseif ($_SESSION['level'] == 'wali') {
                    $queryWali = select($c, 'users a', ['join' => 'INNER JOIN wali b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
                    $dataWali = mysqli_fetch_assoc($queryWali);

                    $join = "INNER JOIN wali b ON a.id_wali=b.id_wali";
                    $where = "b.id_wali=" . $dataWali['id_wali'];
                    $querySiswa = select($c, 'siswa a', ['join' => $join, 'where' => $where]);
                  }

                  if ($querySiswa->num_rows > 0) :
                    foreach ($querySiswa as $dataSiswa) :
                  ?>
                <option value="<?= $dataSiswa['id_siswa'] ?>"><?= $dataSiswa['nama_siswa'] ?></option>
                <?php endforeach;
                  endif; ?>

              </select>
            </div>
            <?php else : ?>
            <div class="col-lg-4 form-group">

              <label for="siswa">Pilih Siswa</label>
              <select name="id_siswa" id="siswa" class="form-control">
                <?php
                  if ($_SESSION['level'] == 'admin') {
                    $querySiswa = select($c, 'siswa');
                  } elseif ($_SESSION['level'] == 'wali') {
                    $queryWali = select($c, 'users a', ['join' => 'INNER JOIN wali b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
                    $dataWali = mysqli_fetch_assoc($queryWali);

                    $join = "INNER JOIN wali b ON a.id_wali=b.id_wali";
                    $where = "b.id_wali=" . $dataWali['id_wali'];
                    $querySiswa = select($c, 'siswa a', ['join' => $join, 'where' => $where]);
                  }

                  if ($querySiswa->num_rows > 0) :
                    foreach ($querySiswa as $dataSiswa) :
                  ?>
                <option value="<?= $dataSiswa['id_siswa'] ?>"><?= $dataSiswa['nama_siswa'] ?></option>
                <?php endforeach;
                  endif; ?>

              </select>
            </div>
            <?php endif; ?>
          </div>
          <button type="submit" name="laporan" class="btn btn-primary">Laporan Data Siswa</button>
        </form>
      </div>

    </div>
  </div>
</div>