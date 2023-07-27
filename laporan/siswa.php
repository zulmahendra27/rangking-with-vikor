<div class="text-center">
  <h1 class="font-weight-bold mb-0"><?= $WEB_NAME ?></h1>
  <p class="mb-4"><?= $ALAMAT_SEKOLAH ?></p>
</div>
<div class="d-flex justify-content-center mb-4">
  <h2 class="font-weight-bold">Laporan Data <?= ucwords($_GET['p']) ?></h2>
</div>

<div class="pt-3">
  <?php
  $semester = "-";
  $querySemester = select($c, 'semester', ['where' => "id_semester=" . $_POST['semester']]);
  if ($querySemester->num_rows > 0) {
    $dataSemester = mysqli_fetch_assoc($querySemester);
    $semester = $dataSemester['nama_semester'] . " - TP. " . $dataSemester['tahun'] . "/" . ($dataSemester['tahun'] + 1);
  }
  ?>
  <h4 class="font-weight-bold mx-4 mb-4">Semester <?= $semester ?></h4>
  <table class="table table-striped table-bordered mx-4">
    <thead>
      <tr class="text-center">
        <th>No.</th>
        <th>NISN</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Nama Wali</th>
        <th>Email</th>
        <th>No. HP</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $join = 'INNER JOIN users ON siswa.id_user=users.id_user INNER JOIN rombel ON siswa.id_rombel=rombel.id_rombel INNER JOIN wali ON siswa.id_wali=wali.id_wali';
      $select = "users.username, users.level, siswa.*, rombel.*, wali.id_wali, wali.nama_wali";
      if (isset($_POST['laporan_siswa_perrombel'])) {
        $id_rombel = mysqli_escape_string($c, htmlspecialchars($_POST['rombel_semester']));
        $semester = mysqli_escape_string($c, htmlspecialchars($_POST['semester']));
        $whereSiswa = "siswa.id_rombel=$id_rombel";
        $query = select($c, 'siswa', ['join' => $join, 'select' => $select, 'where' => $whereSiswa]);
      } else {
        $query = select($c, 'siswa', ['join' => $join, 'select' => $select]);
      }

      if ($query->num_rows > 0) :
        $i = 1;
        foreach ($query as $data) :
      ?>
          <tr>
            <td class="text-center"><?= $i ?></td>
            <td><?= $data['nisn'] ?></td>
            <td><?= $data['nama_siswa'] ?></td>
            <td><?= $data['nama_rombel'] ?></td>
            <td><?= $data['nama_wali'] ?></td>
            <td><?= $data['email_siswa'] ?></td>
            <td><?= $data['nohp_siswa'] ?></td>
          </tr>
        <?php $i++;
        endforeach;
      else : ?>
        <tr>
          <td colspan="7" class="text-center">-- Tidak ada data</td>
        </tr>
      <?php endif; ?>
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
</div>