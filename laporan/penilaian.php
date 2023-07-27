<div class="text-center">
  <h1 class="font-weight-bold mb-0"><?= $WEB_NAME ?></h1>
  <p class="mb-4"><?= $ALAMAT_SEKOLAH ?></p>
</div>
<div class="d-flex justify-content-center mb-4">
  <h2 class="font-weight-bold">Laporan Data <?= ucwords($_GET['p']) ?></h2>
</div>

<div class="pt-3">
  <?php if (isset($_POST['laporan_permapel'])) :
    $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['rombel']));
    $id_mapel = mysqli_escape_string($c, htmlspecialchars($_POST['id_mapel']));

    $rombel = mysqli_fetch_assoc(select($c, 'rombel', ['where' => "id_rombel=$id_rombel"]));
    $mapel = mysqli_fetch_assoc(select($c, 'mapel', ['where' => "id_mapel=$id_mapel"]));
  ?>
  <table class="mb-4">
    <tr>
      <th style="width:150px;">Rombel</th>
      <th>: <?= $rombel['nama_rombel'] ?></th>
    </tr>
    <tr>
      <th>Mapel</th>
      <th>: <?= $mapel['nama_mapel'] ?></th>
    </tr>
  </table>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Siswa</th>
        <th>Nilai</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $i = 1;

        $joinNilai = "INNER JOIN siswa b ON a.id_siswa=b.id_siswa";
        $whereNilai = "a.id_mapel=$id_mapel AND b.id_rombel=$id_rombel";
        $queryNilai = select($c, 'penilaian a', ['join' => $joinNilai, 'where' => $whereNilai]);
        $querySiswa = select($c, 'siswa', ['where' => "id_rombel=$id_rombel"]);

        $arrayNilai = array();
        foreach ($queryNilai as $dataNilai) {
          $arrayNilai[$dataNilai['id_siswa']] = $dataNilai['nilai'];
        }

        if ($querySiswa->num_rows > 0) :
          foreach ($querySiswa as $dataSiswa) :
            $nilai = array_key_exists($dataSiswa['id_siswa'], $arrayNilai) ? $arrayNilai[$dataSiswa['id_siswa']] : 0;
        ?>
      <tr>
        <th class="text-center"><?= $i ?></th>
        <th><?= $dataSiswa['nama_siswa'] ?></th>
        <th><?= $nilai ?></th>
      </tr>
      <?php
            $i++;
          endforeach;
        else : ?>
      <tr class="text-center">
        <th colspan="3">-- Tidak ada data --</th>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <?php
  elseif (isset($_POST['laporan_persiswa'])) :
    if ($_SESSION['level'] == 'siswa') {
      $querySiswa = select($c, 'users a', ['join' => 'INNER JOIN siswa b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
      $dataSiswa = mysqli_fetch_assoc($querySiswa);

      $id_siswa = $dataSiswa['id_siswa'];
    } else {
      $id_siswa = mysqli_escape_string($c, htmlspecialchars($_POST['id_siswa']));
    }

    $siswa = mysqli_fetch_assoc(select($c, 'siswa a', ['where' => "id_siswa=$id_siswa", 'join' => "INNER JOIN rombel b ON a.id_rombel=b.id_rombel"]));
  ?>
  <table class="mb-4">
    <tr>
      <th style="width:150px;">Nama Siswa</th>
      <th>: <?= $siswa['nama_siswa'] ?></th>
    </tr>
    <tr>
      <th>Kelas</th>
      <th>: <?= $siswa['nama_rombel'] ?></th>
    </tr>
  </table>
  <table class="table table-striped table-bordered">
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
          $where = "a.id_siswa=$id_siswa";
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
          $dataGuru = mysqli_fetch_assoc(select($c, 'pengampu a', ['where' => $where, 'join' => $join])); ?>
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

  <table style="width: 100%;" class="mt-4">
    <tr>
      <th style="width: 60%;"></th>
      <th>Tapanuli Tengah, <?= bulanIndo(date('Y-m-d')) ?></th>
    </tr>
    <tr>
      <th></th>
      <th>Mengetahui,</th>
    </tr>
    <tr>
      <th style="padding-bottom: 4rem;"></th>
      <th style="padding-bottom: 4rem;">Kepala Sekolah</th>
    </tr>
    <?php
    $joinGuru = "INNER JOIN users b ON a.id_user=b.id_user";
    $queryGuru = select($c, 'guru a', ['join' => $joinGuru, 'where' => "b.level='kepsek'"]);
    $dataKepsek = mysqli_fetch_assoc($queryGuru);
    ?>
    <tr>
      <th></th>
      <th><?= $dataKepsek['nama_guru'] ?></th>
    </tr>
    <tr>
      <th></th>
      <th>NIP. <?= $dataKepsek['nip'] ?></th>
    </tr>
  </table>
  <?php endif; ?>
</div>