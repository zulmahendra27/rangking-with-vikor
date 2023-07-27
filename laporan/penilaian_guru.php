<div class="text-center">
  <h1 class="font-weight-bold mb-0"><?= $WEB_NAME ?></h1>
  <p class="mb-4"><?= $ALAMAT_SEKOLAH ?></p>
</div>
<div class="d-flex justify-content-center mb-4">
  <h2 class="font-weight-bold">Laporan Data <?= ucwords(str_replace('_', ' ', $_GET['p'],)) ?></h2>
</div>

<div class="pt-3">
  <?php
  $semester = "-";
  $querySemester = select($c, 'semester', ['where' => "id_semester=" . $_POST['semester_penilaian']]);
  if ($querySemester->num_rows > 0) {
    $dataSemester = mysqli_fetch_assoc($querySemester);
    $semester = $dataSemester['nama_semester'] . " - TP. " . $dataSemester['tahun'] . "/" . ($dataSemester['tahun'] + 1);
  }
  ?>
  <h4 class="font-weight-bold mx-4 mb-4">Semester <?= $semester ?></h4>
  <table class="table table-striped table-bordered mx-4">
    <thead>
      <tr class="text-center">
        <th>Rangking</th>
        <th>Nama Guru</th>
        <th>Nilai</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $semester = "-";
      $join = 'INNER JOIN guru b ON a.id_guru=b.id_guru';
      if (isset($_POST['laporan_penilaian_guru_persemester'])) {
        $id_semester = mysqli_escape_string($c, htmlspecialchars($_POST['semester_penilaian']));
        $query = select($c, 'hasilnilai a', ['join' => $join, 'where' => "id_semester=$id_semester"]);
      } else {
        $query = select($c, 'hasilnilai a', ['join' => $join]);
      }

      if ($query->num_rows > 0) :
        $i = 1;
        foreach ($query as $data) :
      ?>
          <tr>
            <td class="text-center"><?= $i ?></td>
            <td><?= $data['nama_guru'] ?></td>
            <td><?= $data['nilai'] ?></td>
          </tr>
        <?php $i++;
        endforeach;
      else : ?>
        <tr>
          <td colspan="3" class="text-center">-- Tidak ada data</td>
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